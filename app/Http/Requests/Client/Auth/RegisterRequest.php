<?php

namespace App\Http\Requests\Client\Auth;

use App\Http\Requests\ApiRequest;

class RegisterRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $clientTypes = array_values(config('constants.client.types'));

        $rules = [
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|min:8|confirmed',
            'name' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', $clientTypes),
        ];

        return $rules;
    }
}
