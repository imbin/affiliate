<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BasePageListPost;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property $image file
 */
class ImageUploadPost extends BasePageListPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|image|max:'.(5 * 1024),//5M
        ];
    }

    public function attributes()
    {
        return [
            'image' => '图片文件',
        ];
    }
}
