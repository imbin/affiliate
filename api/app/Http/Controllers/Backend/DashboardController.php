<?php
/**
 * @author: tobinzhao@gmail.com
 * Date: 2019-11-16
 */

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Services\Backend\UserService;

class DashboardController extends Controller
{
    private $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }
    public function actionInfo()
    {
        /*
昨日新增注册联盟客：10个
今日新增注册联盟客：5个
本月新增注册联盟客：10个
截止昨日联盟客总数：100个

昨日新增有效订单：10单
本月订单销售：10000元
本月提现佣金：10元
审核中的提现单：10单
         * */
        //昨日
        $yesterday = strtotime('-1 day');
        $startTime = date('Y-m-d', $yesterday).' 00:00:00';
        $endTime = date('Y-m-d', $yesterday).' 23:59:59';
        $yesterdayUsers = $this->userService->countByRegTime( $startTime, $endTime);

        //总数，截止昨日
        $totalUsers = $this->userService->countByRegTime( '', $endTime);

        //今日
        $startTime = date('Y-m-d').' 00:00:00';
        $endTime = date('Y-m-d').' 23:59:59';
        $todayUsers = $this->userService->countByRegTime( $startTime, $endTime);

        //本月新增
        $startTime = date('Y-m-1').' 00:00:00';
        $endTime = date('Y-m-d', $yesterday).' 23:59:59';
        $monthUsers = $this->userService->countByRegTime( $startTime, $endTime);


        return $this->jsonSuccess([
            'users' => [
                'yesterday' => $yesterdayUsers,
                'today' => $todayUsers,
                'month' => $monthUsers,
                'total' => $totalUsers,
            ],
            'orders' => [
                //昨日新增有效订单
                'yesterday' => 0,
                //本月订单销售
                'monthSale' => 0,
                //本月提现佣金
                'monthCommission' => 0,
                //审核中的提现单
                'withdrawPending' => 0
            ]
        ]);
    }
}