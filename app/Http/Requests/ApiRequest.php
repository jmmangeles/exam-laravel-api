<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    /**
     * Include route parameters to be validated
     * @method override
     * @return array
     */
    public function all($keys = NULL)
    {
        return array_replace_recursive(
            parent::all(),
            // all route parameters will be added to the validation
            $this->route()->parameters()
        );
    }

    public function withValidator($validator) {
        if (!$validator->fails()){
            $validator->after(function($validator) {
                if (method_exists($this, 'validate')) {
                    $this->validate($validator);
                }
            });
        }
    }
}
