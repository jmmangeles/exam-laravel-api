<?php

namespace App\Http\Controllers\Client\Auth\Register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Client\Auth\RegisterRequest;

use App\Http\Resources\ClientResource;
use App\Models\Client;

use App\Services\CodeGeneration;

class RegisterController extends Controller
{
    protected $expiresIn;

    public function __construct()
    {
        $this->expiresIn = config('jwt.expiration.user');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegisterRequest $request)
    {
        $codeGenerator = new CodeGeneration();
        $code = $codeGenerator->generateCode();

        $input = $request->validated();

        $input['code'] = $code;

        $client = Client::create($input);

        return new ClientResource($client);
    }
}
