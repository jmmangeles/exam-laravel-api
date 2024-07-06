<?php

namespace App\Http\Requests\Client\Auth;

use App\Http\Requests\ApiRequest;

class LoginRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userTypes = array_values(config('constants.client.types'));

        return [
            'email' => 'required|string',
            'password' => 'required|string',
            'fcm_token' => 'nullable|string',
            'device_uuid' => 'nullable|string',
            'device_os' => 'nullable|string',
            'type' => 'nullable|in:' . implode(',', $userTypes)
        ];
    }
}
