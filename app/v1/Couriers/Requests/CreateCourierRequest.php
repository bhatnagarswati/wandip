<?php

namespace App\v1\Couriers\Requests;

use App\v1\Base\BaseFormRequest;

class CreateCourierRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:couriers'],
            'cost' => ['required_if:is_free,0']
        ];
    }
}
