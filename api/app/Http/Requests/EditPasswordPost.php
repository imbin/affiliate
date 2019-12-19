<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class EditPasswordPost
 * @package App\Http\Requests
 *
 * @property $passwordOld string
 * @property $passwordNew string
 * @property $passwordTwice string
 */
class EditPasswordPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'passwordOld' => 'required|min:4|max:20|regex:/^([a-zA-Z0-9\.\-_\/\+=\.\~\!@#\$\%\^\&\*\(\)\[\]\{\}]){4,20}$/',
            'passwordNew' => 'required|min:4|max:20|regex:/^([a-zA-Z0-9\.\-_\/\+=\.\~\!@#\$\%\^\&\*\(\)\[\]\{\}]){4,20}$/',
            'passwordTwice' => 'required|same:passwordNew',
        ];
    }

    public function attributes()
    {
        return [
            'passwordOld' => '旧密码',
            'passwordNew' => '新密码',
            'passwordTwice' => '确认新密码',
        ];
    }
}
