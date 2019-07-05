<?php

namespace App\v1\Carts\Requests;

use App\v1\Base\BaseFormRequest;

class AddToCartRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product' => ['required', 'integer'],
            'quantity' => ['required'],
            'productSizes' => ['required']
        ];
    }
}
