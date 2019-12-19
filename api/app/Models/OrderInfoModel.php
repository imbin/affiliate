<?php
/**
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-24
 */

namespace App\Models;

/**
 * This is the model class for table "order_info".
 *
 * @property int $id
 * @property int $user_id 所属用户
 * @property string $order_sn 订单号
 * @property string $order_amount 订单金额（不含运费）
 * @property int $order_status 订单状态:1=未付款、2=已取消、3=已付款、4=已发货
 * @property string $order_time 下单日期
 * @property string $pay_time 支付日期
 * @property string $deliver_time 发货日期
 * @property int $commission_status 佣金状态:1=未发放、2=已发放、3=不发放
 * @property string $commission 订单佣金
 * @property int $audit_days 佣金发放周期,默认30天
 * @property string $create_time 创建日期
 * @property string $update_time 更新日期
 */
class OrderInfoModel extends ValidateBaseModel
{

    protected $table = 'order_info';
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
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-24
     *
     * @param $orderSn
     *
     * @return $this
     */
    public function findBySn($orderSn)
    {
        return static::query()->where('order_sn', '=', $orderSn)->first();
    }
}
