<?php

namespace App\Http\Requests\Backend;

use App\Enum\BannerEnum;
use App\Enum\OrderEnum;
use App\Http\Requests\BasePageListPost;
use App\Models\OrderGoodsModel;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterPost
 * @package App\Http\Requests
 *
 * @property int $order_status 订单状态:1=未付款、2=已取消、3=已付款、4=已发货
 * @property string $order_time 下单日期
 * @property string $pay_time 支付日期
 * @property string $deliver_time 发货日期
 */
class OrderEditPost extends BasePageListPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_status' => 'required|integer|in:'.implode(',', [
                    OrderEnum::STATUS_PENDING,
                    OrderEnum::STATUS_CANCELLED,
                    OrderEnum::STATUS_PAYED,
                    OrderEnum::STATUS_DELIVERED,
                ]),
            'order_time' => 'required|date|date_format:Y-m-d H:i:s',
            'pay_time' => 'nullable|date|date_format:Y-m-d H:i:s',
            'deliver_time' => 'nullable|date|date_format:Y-m-d H:i:s',
        ];
    }

    public function attributes()
    {
        return [
            'order_status' => '订单状态',
            'order_time' => '下单日期',
            'pay_time' => '支付日期',
            'deliver_time' => '发货日期',
        ];
    }
}
