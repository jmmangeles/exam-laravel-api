<?php

namespace App\Http\Requests\Client\Customer;

use App\Http\Requests\ApiRequest;

class UpdateProfileRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
        $userId = auth()->id();

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $userId,
        ];
    }
}
