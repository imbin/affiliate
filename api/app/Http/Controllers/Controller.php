<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * Author: zhaobin
     * Date: 2019-11-07
     *
     * @param string|array|object $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonSuccess($data = null)
    {
        return response()->json([
            'code' => 0,
            'msg' => __('base.success'),
            'data' => $data
        ]);
    }

    /**
     *
     * Author: zhaobin
     * Date: 2019-11-07
     *
     * @param int $code
     * @param string $msg
     * @param string|array|object $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonFail(int $code, string $msg, $data = null)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }


}
