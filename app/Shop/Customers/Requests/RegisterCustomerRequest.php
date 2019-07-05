<?php

namespace App\Shop\Customers\Requests;

use App\Shop\Base\BaseFormRequest;

class RegisterCustomerRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:servicers|unique:customers|unique:drivers,driverEmail',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'required|min:10',
            'countryCode' => 'required',
            
        ];
    }
}
