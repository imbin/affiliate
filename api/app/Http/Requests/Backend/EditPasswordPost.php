<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property $realName string
 * @property $passwordOld string
 * @property $passwordNew string
 * @property $passwordTwice string
 */
class EditPasswordPost extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'realName' => 'required|min:1|max:20',
            'passwordOld' => 'nullable|min:4|max:20|regex:/^([a-zA-Z0-9\.\-_\/\+=\.\~\!@#\$\%\^\&\*\(\)\[\]\{\}]){4,20}$/',
            'passwordNew' => 'required_with:passwordOld|min:4|max:20|regex:/^([a-zA-Z0-9\.\-_\/\+=\.\~\!@#\$\%\^\&\*\(\)\[\]\{\}]){4,20}$/',
            'passwordTwice' => 'required_with:passwordNew|same:passwordNew',
        ];
    }

    public function attributes()
    {
        return [
            'realName' => '姓名',
            'passwordOld' => '旧密码',
            'passwordNew' => '新密码',
            'passwordTwice' => '确认新密码',
        ];
    }
}
