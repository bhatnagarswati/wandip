<?php

namespace App\v1\Carts\Requests;

use App\v1\Base\BaseFormRequest;

class PayPalCheckoutExecutionRequest extends BaseFormRequest implements CheckoutInterface
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paymentId' => ['required'],
            'PayerID' => ['required'],
            'billing_address' => ['required'],
            'payment' => ['required']
        ];
    }
}
