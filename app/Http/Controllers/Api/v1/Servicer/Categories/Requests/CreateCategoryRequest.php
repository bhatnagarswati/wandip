<?php

namespace App\v1\Categories\Requests;

use App\v1\Base\BaseFormRequest;

class CreateCategoryRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:categories']
        ];
    }
}
