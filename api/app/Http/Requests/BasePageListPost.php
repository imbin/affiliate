<?php

namespace App\Http\Requests;


use App\Enum\CodeEnum;
use Illuminate\Foundation\Http\FormRequest;

/**
 * 分页列表基础表单
 * @property $page int
 * @property $perPage int
 *
 */
class BasePageListPost extends FormRequest
{

    public function rules()
    {
        return [
            'page' => 'required|integer|min:1',
            'perPage' => 'required|integer|min:5|max:100',
        ];
    }

    public function attributes()
    {
        return [
            'page' => '页码',
            'perPage' => '每页数量'
        ];
    }
}