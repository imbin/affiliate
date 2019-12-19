<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BasePageListPost;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property $id string
 * @property $userName string
 * @property $nickName string
 * @property $gender int
 */
class UserListPost extends BasePageListPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'nullable|integer|min:1',
            'userName' => 'nullable|min:1|max:20|regex:/^([a-zA-Z0-9]){1,20}$/',
            'nickName' => 'nullable|min:1|max:20',
        ];
    }

    public function attributes()
    {
        return [
            'userName' => '登录名',
            'nickName' => '昵称',
        ];
    }
}
