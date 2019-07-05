<?php

namespace App\Shop\Servicers\Requests;

use App\Shop\Base\BaseFormRequest;

class RegisterServicerRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'servicer_name' => 'required|string|max:255',
            'servicer_email' => 'required|string|email|max:255|unique:servicers,email|unique:customers,email|unique:drivers,driverEmail',
            'servicer_password' => 'required|string|min:6|confirmed',
            'servicer_phone' => 'required|min:10',
            'servicer_countryCode' => 'required',
        ];
    }
}
