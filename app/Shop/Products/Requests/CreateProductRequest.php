<?php

namespace App\Shop\Products\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateProductRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:products'],
            'quantity' => ['required', 'numeric'],
            'price' => ['required'],
            'delivery_charges' => ['required'],
            'productSizes' => ['required'],
            'serviceOfferedType' => ['required'],
            'cover' => ['required', 'file', 'image:png,jpeg,jpg,gif'],
        ];
    }
}
