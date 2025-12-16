<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/api', function () {
    return response()->json([
        'code' => 0,
        'msg' => 'Unknown request.'
    ]);
});

//不用检查登录的
Route::group(['prefix' => '/api', 'domain' => env('HOST_WEB'), 'namespace' => 'Frontend'], function () {
    Route::post('login', 'UserController@actionLogin');
    Route::post('register', 'UserController@actionRegister');

    //banner
    Route::get('banners', 'BannerController@actionList');
});

//用户中心要检查登录的
Route::group(['prefix' => '/api/user', 'domain' => env('HOST_WEB'), 'middleware' => 'jwt:frontend', 'namespace' => 'Frontend'], function () {
    Route::get('profile', 'UserController@actionGetProfile');
    Route::put('edit-profile', 'UserController@actionEditProfile');
    Route::put('edit-pwd', 'UserController@actionEditPwd');

    //订单
    Route::get('orders', 'OrderController@actionList');
    //收支明细
    Route::get('trades', 'FinanceController@actionTradeList');
    //查余额
    Route::get('balance', 'FinanceController@actionBalance');
    //提现申请
    Route::post('withdraws', 'FinanceController@actionWithdrawCreate');
    //提现列表
    Route::get('withdraws', 'FinanceController@actionWithdrawList');
});
