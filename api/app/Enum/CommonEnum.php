<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-06
 * Time: 23:56
 */

namespace App\Enum;


class CommonEnum
{
    const IS_DISABLED_YES = 1;
    const IS_DISABLED_NO = 0;
    const DISABLED_TEXT_LIST = [
        self::IS_DISABLED_NO => '否',
        self::IS_DISABLED_YES => '是',
    ];

    const TRADE_TYPE_INCOME = 1;//收入
    const TRADE_TYPE_EXPEND = 2;//支出

    const FINANCE_TRADE_TYPE_TEXT_LIST = [
        self::TRADE_TYPE_INCOME => '收入',
        self::TRADE_TYPE_EXPEND => '支出',
    ];

    //提现状态:1=未审核、2=驳回、3=提现成功
    const WITHDRAW_STATUS_PENDING = 1;
    const WITHDRAW_STATUS_REJECT = 2;
    const WITHDRAW_STATUS_COMPLETE = 3;

    const WITHDRAW_STATUS_TEXT_LIST = [
        self::WITHDRAW_STATUS_PENDING => '待审核',
        self::WITHDRAW_STATUS_REJECT => '驳回',
        self::WITHDRAW_STATUS_COMPLETE => '提现成功',
    ];

    //提现方式：1=银行卡，2=支付宝
    const WITHDRAW_WAY_BANK = 1;
    const WITHDRAW_WAY_ALIPAY = 2;

    const WITHDRAW_WAY_TEXT_LIST = [
        self::WITHDRAW_WAY_BANK => '银行卡',
        self::WITHDRAW_WAY_ALIPAY => '支付宝',
    ];

    const WITHDRAW_SN_PREFIX = 'WD';
}