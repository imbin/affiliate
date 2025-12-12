<?php
/**
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-09
 */

namespace App\Utils;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UtilHelper
{
    /**
     * 获取IP
     * @return string
     */
    public static function getIp()
    {
        static $ip;
        if (!$ip) {
            if (PHP_SAPI == 'cli') {
                $ip = 'CLI';
            } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) { //透过代理服务器取得客户端的真实 IP 地址
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) { //客户端IP
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif (isset($_SERVER["REMOTE_ADDR"])) { //正在浏览当前页面用户的 IP 地址
                $ip = $_SERVER["REMOTE_ADDR"];
            } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $ip = getenv("HTTP_CLIENT_IP");
            } elseif (getenv("REMOTE_ADDR")) {
                $ip = getenv("REMOTE_ADDR");
            } else {
                $ip = "UnknownIP";
            }
        }
        return $ip;
    }

    public static function hashPassword(string $pwd)
    {
        return Hash::make($pwd);
    }

    /**
     * 验证密码


     *
     * @param string $hashedPwd
     * @param string $input
     *
     * @return bool
     */
    public static function validPassword(string $input, string $hashedPwd)
    {
        return Hash::check($input, $hashedPwd);
    }

    /**
     * 生成动态缩略图


     *
     * @param string $picUrl
     *
     * @return mixed
     */
    public static function thumbUrl(string $picUrl, int $width = 100, int $height = 100, int $quality = 80)
    {
        return str_replace('/storage/', sprintf('/thumb_%dx%d_%d/', $width, $height, $quality), $picUrl);
    }

    /**
     * 生成随机单号


     *
     * @param int $length
     *
     * @return string
     */
    public static function generateSn(string $prefix, int $length = 20)
    {
        $sn = $prefix.self::logTime('YmdHisu').Str::random(8);
        if ($length) {
            return substr($sn, 0, $length);
        }
        return $sn;
    }
    public static function logTime($format = 'Y-m-d H:i:s.u')
    {
        $timeZone = new \DateTimeZone( env( 'APP_TIMEZONE', 'UTC'));
        $now = new \DateTime( 'now',  $timeZone );
        return $now->format($format);
    }
}