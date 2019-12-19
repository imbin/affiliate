<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-06
 * Time: 23:56
 */

namespace App\Enum;


class UserEnum
{
    const STATUS_PENDING = 1;//待审核
    const STATUS_PASS = 2;//审核通过
    const STATUS_REJECT = 3;//驳回

    const STATUS_TEXT_LIST = [
        self::STATUS_PENDING => '待审核',
        self::STATUS_PASS => '审核通过',
        self::STATUS_REJECT => '驳回',
    ];
}