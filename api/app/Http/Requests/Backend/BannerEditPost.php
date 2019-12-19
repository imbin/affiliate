<?php

namespace App\Http\Requests\Backend;

use App\Enum\BannerEnum;
use App\Http\Requests\BasePageListPost;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property string $title 标题
 * @property string $pic_url 图片URL
 * @property int $status 状态：1=已上架、2=已下架
 * @property string $redirect_url 跳转 URL
 * @property string $sku SKU
 * @property string $price 价格
 * @property string $return_type 返佣方式
 * @property string $return_value 返佣金额/比例
 *
 */
class BannerEditPost extends BasePageListPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|integer|in:1,2',
            'title' => 'required|min:1|max:64',
            'redirect_url' => 'required|min:1|max:128',
            'pic_url' => 'required|min:1|max:128',
            'sku' => 'required|max:20',
            'price' => 'required|max:20',
            'return_type' => 'required|numeric|in:'.BannerEnum::RETURN_TYPE_AMOUNT.','.BannerEnum::RETURN_TYPE_PERCENT,
            'return_value' => 'required|numeric|min:0.01',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'pic_url' => '图片URL',
            'redirect_url' => '跳转 URL',
            'status' => '上架状态',
            'sku' => 'SKU',
            'price' => '售价',
            'return_type' => '返佣方式',
            'return_value' => '返佣金额/比例',
        ];
    }
}
