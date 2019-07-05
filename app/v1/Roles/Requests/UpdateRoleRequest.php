<?php

namespace App\v1\Roles\Requests;

use App\v1\Base\BaseFormRequest;

class UpdateRoleRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'display_name' => ['required'],
            'roles' => ['array']
        ];
    }
}
