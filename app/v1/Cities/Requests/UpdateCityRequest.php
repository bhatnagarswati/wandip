<?php

namespace App\v1\Cities\Requests;

use App\v1\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('cities')->ignore(request()->segment(7))]
        ];
    }
}
