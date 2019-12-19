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
 * @property int $user_id 所属用户
 * @property string $order_sn 订单号
 * @property int $order_status 订单状态:1=未付款、2=已取消、3=已付款、4=已发货
 * @property string $order_time 下单日期
 * @property string $pay_time 支付日期
 * @property string $deliver_time 发货日期
 * @property OrderGoodsModel[] $goods_list 订单商品列表
 *
 * @property int $sku sku
 * @property int $sku_quantity SKU数量
 * @property string $sku_price 单价
 * @property string $subtotal 小计金额=SKU单价 x SKU数量
 */
class OrderCreatePost extends BasePageListPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|min:1',
            'order_sn' => 'required|min:1|max:64',
            'order_status' => 'required|integer|in:'.implode(',', [
                    OrderEnum::STATUS_PENDING,
                    OrderEnum::STATUS_CANCELLED,
                    OrderEnum::STATUS_PAYED,
                    OrderEnum::STATUS_DELIVERED,
                ]),
            'order_time' => 'required|date|date_format:Y-m-d H:i:s',
            'pay_time' => 'nullable|date|date_format:Y-m-d H:i:s',
            'deliver_time' => 'nullable|date|date_format:Y-m-d H:i:s',
            'goods_list' => 'required|array',
            'goods_list.*.sku' => 'required|integer|min:1',
            'goods_list.*.sku_quantity' => 'required|integer|min:1',
            'goods_list.*.sku_price' => 'required|numeric|min:0',
        ];
    }

    public function attributes()
    {
        return [
            'order_sn' => '订单号',
            'order_status' => '订单状态',
            'order_time' => '下单时间',
            'pay_time' => '付款时间',
            'deliver_time' => '发货时间',
            'goods_list' => '商品列表',
            'goods_list.*.sku' => 'SKU',
            'goods_list.*.sku_quantity' => '商品数量',
            'goods_list.*.sku_price' => '售价',
        ];
    }
}
