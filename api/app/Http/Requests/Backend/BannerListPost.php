<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BasePageListPost;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property $title string
 * @property $status int
 */
class BannerListPost extends BasePageListPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'nullable|integer|in:1,2',
            'title' => 'nullable|min:1|max:50',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'status' => '上架状态',
        ];
    }
}
