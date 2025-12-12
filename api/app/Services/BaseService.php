<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-08
 * Time: 01:15
 */

namespace App\Services;

class BaseService
{
    private static $_instance = null;

    /**
     * @return $this
     */
    public static function singleton()
    {
        return app()->make(static::class);
    }
}