<?php

namespace App\v1\Cart\Requests;

use App\v1\Base\BaseFormRequest;

/**
 * Class CartCheckoutRequest
 * @package App\v1\Cart\Requests
 * @codeCoverageIgnore
 */
class CartCheckoutRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'billing_address' => ['required']
        ];
    }
}
