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

//不检查登录的
Route::group(['prefix' => '/api', 'domain' => env('HOST_ADMIN'), 'namespace' => 'Backend'], function () {
    Route::post('login', 'AdminUserController@actionLogin');

});
//管理员
Route::group(['prefix' => '/api', 'domain' => env('HOST_ADMIN'), 'middleware' => 'jwt:backend', 'namespace' => 'Backend'], function () {
//上传图片
    Route::post('upload/img', 'UploadController@actionImage');
    //管理员
    Route::post('admin', 'AdminUserController@actionCreate');
    //修改自己的资料
    Route::put('admin/pwd', 'AdminUserController@actionEditPwd');
    //管理员列表
    Route::post('admin/list', 'AdminUserController@actionList');
    //删除管理员
    Route::delete('admin/{id}', 'AdminUserController@actionDelete')->where(['id' => '[0-9]+']);
    //修改管理员
    Route::put('admin/edit/{id}', 'AdminUserController@actionEdit')->where(['id' => '[0-9]+']);

    //联盟客列表
    Route::post('users/list', 'UserController@actionList');
    //操作联盟客状态：审核通过、驳回、禁用、启用
    Route::patch('users/{id}', 'UserController@actionStatus')->where(['id' => '[0-9]+']);
    //修改联盟客
    Route::put('users/edit/{id}', 'UserController@actionEdit')->where(['id' => '[0-9]+']);

    //后台首页
    Route::get('dashboard', 'DashboardController@actionInfo');

    //素材Banner(CRUD)
    Route::get('banners', 'BannerController@actionList');
    Route::post('banners', 'BannerController@actionCreate');
    Route::post('banners/{id}', 'BannerController@actionEdit')->where(['id' => '[0-9]+']);
    Route::delete('banners/{id}', 'BannerController@actionDelete')->where(['id' => '[0-9]+']);

    //订单列表
    Route::get('orders', 'OrderController@actionList');
    //录入订单
    Route::post('orders', 'OrderController@actionCreate');
    //录入订单
    Route::post('orders/{id}', 'OrderController@actionEdit')->where(['id' => '[0-9]+']);
    //发放佣金
    Route::put('orders/grant/{id}', 'OrderController@actionGrant')->where(['id' => '[0-9]+']);

    //提现列表
    Route::get('withdraws', 'WithdrawController@actionList');
    //提现审核
    Route::put('withdraws/{id}', 'WithdrawController@actionUpdateStatus')->where(['id' => '[0-9]+']);
    //联盟客收支明细
    Route::get('trades', 'TradeController@actionList');
});

