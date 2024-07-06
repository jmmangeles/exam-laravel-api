<?php

namespace App\Http\Controllers\Client\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Controller;

use App\Http\Resources\ClientResource;

use App\Http\Requests\Client\Auth\LoginRequest;
use App\Models\ClientAccess;

use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $decayMinutes = 5; // minutes to lockout
    protected $maxAttempts = 5; // number of attempts before lockout

    protected $expiresIn;

    public function __construct()
    {
        $this->middleware('auth:client', ['except' => ['login']]);
        $this->expiresIn = config('jwt.expiration.user');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $this->guard()->setTTL($this->expiresIn);

        $data = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if ($token = $this->guard()->attempt($data)) {

            $this->updateAccess(
                $validated['device_uuid'] ?? NULL,
                $validated['device_os'] ?? NULL,
                $token,
                $validated['fcm_token'] ?? NULL
            );
            $this->clearLoginAttempts($request);
            return $this->respondWithToken($token);
        }

        $this->incrementLoginAttempts($request);
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    private function updateAccess($deviceUuid, $deviceOs, $accessToken, $fcmToken)
    {
        $userId = $this->guard()->id();
        $tokenData = ClientAccess::firstOrNew([
            'client_id' => $userId,
            'device_uuid' => $deviceUuid,
        ]);

        $tokenData->device_os = $deviceOs;
        $tokenData->access_token = $accessToken;
        $tokenData->fcm_token = $fcmToken;
        $tokenData->ip_address = request()->ip();
        $tokenData->last_logged_in = now();
        $tokenData->save();
    }

    private function updateLastAccess($accessToken)
    {
        $userId = $this->guard()->id();
        ClientAccess::where([
            'client_id' => $userId,
            'access_token' => $accessToken,
        ])->update([
            'last_logged_in' => now()
        ]);
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            'email' => [trans('auth.throttleTimer', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }

    public function me(Request $request)
    {
        $type = $this->guard()->user()->type;
        $accessToken = $request->bearerToken();

        $this->updateLastAccess($accessToken);

        $user = new ClientResource($this->guard()->user());
        return response()->json($user);
    }

    public function logout(Request $request)
    {

        $input = $request->validate([
            'device_uuid' => 'nullable|string',
        ]);

        $clientAccess = ClientAccess::where('client_id', $this->guard()->id());

        if (empty($input['device_uuid'])) {
            $clientAccess->whereNull('device_uuid');
        } else {
            $clientAccess->where('device_uuid', $input['device_uuid']);
        }

        $clientAccess->update([
            'access_token' => null,
            'fcm_token' => null
        ]);

        try {
            $this->guard()->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (Exception $e) {
            //empty
        }
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        $user = $this->guard()->user();

        $userResource = new ClientResource($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->expiresIn,
            'user' => $userResource
        ]);
    }

    public function guard()
    {
        return Auth::guard('client');
    }
}
