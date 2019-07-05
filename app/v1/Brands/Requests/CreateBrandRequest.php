<?php

namespace App\v1\Brands\Requests;

use App\v1\Base\BaseFormRequest;

class CreateBrandRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:brands']
        ];
    }
}
