<?php

namespace App\v1\Addresses\Requests;

use App\v1\Base\BaseFormRequest;

class CreateAddressRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'alias' => ['required'],
            'address_1' => ['required']
        ];
    }
}
