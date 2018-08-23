<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('V1')->prefix('v1')->group(function () {

    Route::middleware('api.sign')->group(function () {
        Route::post('login', 'LoginController@login'); // 登录
        Route::post('register', 'RegisterController@register'); // 注册

        // 待接单订单
        Route::prefix('order/wait')->group(function () {
            Route::post('/', 'OrderWaitController@index'); // 列表
            Route::post('show', 'OrderWaitController@show'); // 详情
        });

        Route::post('games', 'GameController@index'); // 游戏
        Route::post('regions', 'RegionController@index'); // 区
        Route::post('servers', 'ServerController@index'); // 服
    });

    // 登录后操作
    Route::middleware(['api.token', 'api.sign'])->group(function () {
        // 登录密码问题
        Route::prefix('password')->group(function () {
            Route::post('refound', 'PasswordController@refound'); // 密码找回
            Route::post('reset', 'PasswordController@reset'); // 重置密码
        });
        // 个人资料
        Route::prefix('profile')->group(function () {
            Route::post('/', 'ProfileController@index'); // 个人资料
            Route::post('update', 'ProfileController@update'); // 个人资料修改
            Route::post('pay-password/set', 'ProfileController@payPasswordSet'); // 设置支付密码
            Route::post('pay-password/reset', 'ProfileController@payPasswordReset'); // 修改支付密码
            Route::post('pay-password/refound', 'ProfileController@payPasswordRefound'); // 找回支付密码
            Route::post('certification', 'ProfileController@certification'); // 填写实名认证
            Route::post('certification/show', 'ProfileController@certificationShow'); // 实名认证详情
        });
        // 财务
        Route::prefix('finance')->group(function () {
            Route::post('flows', 'FinanceController@flows'); // 资金流水
            Route::post('flows/show', 'FinanceController@flowsShow'); // 资金流水详情
            Route::post('withdraw', 'FinanceController@withdraw'); // 提现
            Route::post('recharge', 'FinanceController@recharge'); // 充值
        });
        // 帮助
        Route::prefix('help')->group(function () {
            Route::post('/', 'HelpController@index'); // 帮助列表
            Route::post('show', 'HelpController@show'); // 帮助详情
        });
        // 留言
        Route::prefix('message')->group(function () {
            Route::post('/', 'MessageController@index'); // 帮助列表
            Route::post('show', 'MessageController@show'); // 帮助详情
        });
        // 公告
        Route::prefix('notice')->group(function () {
            Route::post('/', 'NoticeController@index'); // 公告列表
            Route::post('show', 'NoticeController@show'); // 公告详情
        });
        // 订单
        Route::prefix('order')->group(function () {
            // 接单人订单
            Route::prefix('take')->group(function () {
                Route::post('/', 'OrderTakeController@index'); // 列表
                Route::post('show', 'OrderTakeController@show'); // 详情
            });
            // 发单人订单
            Route::prefix('send')->group(function () {
                Route::post('/', 'OrderSendController@index'); // 列表
                Route::post('show', 'OrderSendController@show'); // 详情
            });
            // 订单操作
            Route::prefix('operation')->group(function () {
                Route::post('apply-consult', 'OrderOperationController@applyConsult'); // 申请协商
                Route::post('cancel-consult', 'OrderOperationController@cancelConsult'); // 取消协商
                Route::post('agree-consult', 'OrderOperationController@agreeConsult'); // 同意协商
                Route::post('reject-consult', 'OrderOperationController@rejectConsult'); // 不同意协商
                Route::post('apply-complain', 'OrderOperationController@applyComplain'); // 申请仲裁
                Route::post('cancel-complain', 'OrderOperationController@cancelComplain'); // 取消仲裁
                Route::post('detail', 'OrderOperationController@detail'); // 详情
                Route::post('apply-complete-image', 'OrderOperationController@applyCompleteImage'); // 发送完成截图
                Route::post('complain-message', 'OrderOperationController@complainMessage'); // 发送仲裁证据
                Route::post('get-message', 'OrderOperationController@getMessage'); // 获取留言
                Route::post('send-message', 'OrderOperationController@sendMessage'); // 发送留言
                Route::post('get-complain-info', 'OrderOperationController@getComplainInfo'); // 仲裁详情
            });
        });
    });
});

// 订单操作(天猫发单器)
Route::prefix('tm')->middleware('api.auth')->group(function () {
    Route::post('onsale', 'TmOrderController@onSale'); // 上架
    Route::post('offsale', 'TmOrderController@offSale'); // 下架
    Route::post('delete', 'TmOrderController@delete'); // 撤单
    Route::post('lock', 'TmOrderController@lock'); // 锁定
    Route::post('cancel-lock', 'TmOrderController@cancelLock'); // 取消锁定
    Route::post('complete', 'TmOrderController@complete'); // 完成
    Route::post('apply-consult', 'TmOrderController@applyConsult'); // 申请协商
    Route::post('cancel-consult', 'TmOrderController@cancelConsult'); // 取消协商
    Route::post('agree-consult', 'TmOrderController@agreeConsult'); // 同意协商
    Route::post('reject-consult', 'TmOrderController@rejectConsult'); // 不同意协商
    Route::post('apply-complain', 'TmOrderController@applyComplain'); // 申请仲裁
    Route::post('cancel-complain', 'TmOrderController@cancelComplain'); // 取消仲裁
    Route::post('detail', 'TmOrderController@detail'); // 详情
    Route::post('add-time', 'TmOrderController@addTime'); // 加时
    Route::post('add-money', 'TmOrderController@addMoney'); // 加价
    Route::post('update-account-password', 'TmOrderController@updateAccountPassword'); // 修改账号密码
    Route::post('apply-complete-image', 'TmOrderController@applyCompleteImage'); // 完成的截图
    Route::post('complain-message', 'TmOrderController@complainMessage'); // 仲裁证据
    Route::post('get-message', 'TmOrderController@getMessage'); // 获取留言
    Route::post('send-message', 'TmOrderController@sendMessage'); // 发送留言
    Route::post('get-complain-info', 'TmOrderController@getComplainInfo'); // 仲裁详情
});
Route::post('tm/update', 'TmOrderController@update'); // 修改订单
Route::post('tm/place-order', 'TmOrderController@placeOrder'); // 下单
