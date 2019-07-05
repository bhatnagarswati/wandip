<?php

namespace App\v1\Admins\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServicerRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:servicers'],
            'password' => ['required', 'min:8'],
        ];
    }
}
