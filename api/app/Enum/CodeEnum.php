<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-06
 * Time: 23:56
 */

namespace App\Enum;


class CodeEnum
{
    const BASE_SERVER_ERROR = 1;
    const BASE_INVALID_TOKEN = 2;
    const BASE_INVALID_PARAMETER = 3;//参数验证错误
    const BASE_SQL_ERROR = 3;//SQL错误

    const USER_INVALID_ACCOUNT = 10001;//账号错误
    const USER_STATUS_PENDING = 10002;//账号审核中
    const USER_NAME_EXISTS = 10003;//账号已存在
    const USER_EMAIL_EXISTS = 10004;//Email已存在
    const USER_MOBILE_EXISTS = 10005;//手机号已存在
    const USER_INVALID_OLD_PWD = 10006;//旧密码不正确
    const FINANCE_NOT_ENOUGH_BALANCE = 10007;//余额不足
}