<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property $email string
 * @property $mobile string
 * @property $gender integer
 * @property $birthday date
 *
 */
class EditProfilePost extends FormRequest
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
            'email' => 'email',
            'gender' => 'integer|in:1,2',
            'mobile' => 'numeric|regex:/^1(\d{10})$/i',
            'birthday' => 'date|date_format:Y-m-d',
        ];
    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'mobile' => '手机号',
            'birthday' => '生日',
            'gender' => '性别',
        ];
    }
}
