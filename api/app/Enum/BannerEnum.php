<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-06
 * Time: 23:56
 */

namespace App\Enum;


class BannerEnum
{
    const STATUS_ON = 1;//上架
    const STATUS_OFF = 2;//下架

    const STATUS_TEXT_LIST = [
        self::STATUS_ON => '已上架',
        self::STATUS_OFF => '已下架',
    ];

    const RETURN_TYPE_AMOUNT = 1;
    const RETURN_TYPE_PERCENT = 2;
    const RETURN_TYPE_TEXT_LIST = [
        self::RETURN_TYPE_AMOUNT => '金额',
        self::RETURN_TYPE_PERCENT => '百分比',
    ];
}