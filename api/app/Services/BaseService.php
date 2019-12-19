<?php
/**
 *
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-08
 * Time: 01:15
 */

namespace App\Services;


use App\Http\Requests\BasePageListInterface;

class BaseService
{
    private static $_instance = null;

    /**
     * @author: tobinzhao@gmail.com
     * Date: 2019-11-09
     * @return $this
     */
    public static function singleton()
    {
        return app()->make(static::class);
    }
}