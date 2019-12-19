<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterAdminPost
 * @package App\Http\Requests
 *
 * @property $userName string
 * @property $realName string
 * @property $password string
 */
class RegisterAdminPost extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userName' => 'required|min:1|max:20|regex:/^([a-zA-Z0-9]){4,20}$/',
            'realName' => 'required|min:1|max:20',
            'password' => 'required|min:4|max:20|regex:/^([a-zA-Z0-9\.\-_\/\+=\.\~\!@#\$\%\^\&\*\(\)\[\]\{\}]){4,20}$/',
        ];
    }

    public function attributes()
    {
        return [
            'userName' => '登录名',
            'realName' => '姓名',
            'password' => '登录密码'
        ];
    }
}
