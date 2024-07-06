<?php

namespace App\Http\Controllers\Client\Customer\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Client\Customer\UpdateProfileRequest;

use App\Models\Client;

class UpdateProfile extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateProfileRequest $request)
    {
        $userId = auth()->id();

        $input = $request->validated();

        Client::where('id', $userId)
            ->update([
                'name' => $input['name'],
                'email' => $input['email']
            ]);

        return true;
    }
}
