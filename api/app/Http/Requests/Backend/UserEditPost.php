<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BasePageListPost;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property $password string
 */
class UserEditPost extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:4|max:20|regex:/^([a-zA-Z0-9\.\-_\/\+=\.\~\!@#\$\%\^\&\*\(\)\[\]\{\}]){4,20}$/',
        ];
    }

    public function attributes()
    {
        return [
            'password' => '登录密码',
        ];
    }
}
