<?php

namespace App\v1\OrderStatuses\Requests;

use App\v1\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('order_statuses')->ignore($this->segment('4'))]
        ];
    }
}
