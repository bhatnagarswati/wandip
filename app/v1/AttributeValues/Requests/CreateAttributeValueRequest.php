<?php

namespace App\v1\AttributeValues\Requests;

use App\v1\Base\BaseFormRequest;

class CreateAttributeValueRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => ['required']
        ];
    }
}
