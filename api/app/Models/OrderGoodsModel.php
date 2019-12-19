<?php
/**
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-24
 */

namespace App\Models;
//namespace App\Models;


/**
 * This is the model class for table "order_goods".
 *
 * @property int $id
 * @property int $order_id order id
 * @property string $order_sn 订单号
 * @property int $sku sku
 * @property int $sku_quantity SKU数量
 * @property string $sku_price 单价
 * @property string $subtotal 小计金额=SKU单价 x SKU数量
 * @property string $commission_ratio 佣金比例
 * @property string $sku_commission 佣金=小计金额x佣金比例/100
 * @property string $create_time 创建日期
 * @property string $update_time 更新日期
 */
class OrderGoodsModel extends ValidateBaseModel
{

    protected $table = 'order_goods';
// false = 禁用Laravel时间戳字段
    public $timestamps = false;
//有create_time update_time 就恢复以下三行
//    const CREATED_AT = 'create_time';
//    const UPDATED_AT = 'update_time';
//    protected $dateFormat = 'U';

    /**
     * 获取验证规则
     * Date: 2019-02-25
     *
     * 'title' => 'required|max:255',
     * @return array
     */
    public function getRules()
    {
        return [
            //'merchant_id' => 'required|integer',
            //'title' => 'required|max:255',
        ];
    }

    /**
     * 获取指定订单的商品列表
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-24
     *
     * @param int $orderId
     *
     * @return $this[]
     */
    public function findListByOrderId($orderId)
    {
        if (!is_array($orderId)) {
            $orderId = [$orderId];
        }
        return static::query()->whereIn('order_id', $orderId)->get()->all();
    }
}
