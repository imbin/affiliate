<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-06
 * Time: 23:56
 */

namespace App\Enum;


class OrderEnum
{
    //订单状态：1=未付款、2=已取消、3=已付款、4=已发货
    const STATUS_PENDING = 1;//未付款
    const STATUS_CANCELLED = 2;//已取消
    const STATUS_PAYED = 3;//已付款
    const STATUS_DELIVERED = 4;//已发货

    const STATUS_TEXT_LIST = [
        self::STATUS_PENDING => '未付款',
        self::STATUS_CANCELLED => '已取消',
        self::STATUS_PAYED => '已付款',
        self::STATUS_DELIVERED => '已发货',
    ];

    //佣金状态: 1=未发放、2=已发放、3=不发放
    const COMMISSION_STATUS_PENDING = 1;//未发放
    const COMMISSION_STATUS_GRANTED = 2;//已发放
    const COMMISSION_STATUS_NOT = 3;//不发放

    const COMMISSION_STATUS_TEXT_LIST = [
        self::COMMISSION_STATUS_PENDING => '未发放',
        self::COMMISSION_STATUS_GRANTED => '已发放',
        self::COMMISSION_STATUS_NOT => '不发放',
    ];

    //佣金发放周期,默认30天
    const ORDER_AUDIT_DAYS = 30;


}