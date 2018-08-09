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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->group(function () {
    // 订单操作
    Route::prefix('order')->middleware('api.auth')->group(function () {
        Route::post('onsale', 'OrderController@onSale'); // 上架
        Route::post('offsale', 'OrderController@offSale'); // 下架
        Route::post('delete', 'OrderController@delete'); // 撤单
        Route::post('lock', 'OrderController@lock'); // 锁定
        Route::post('cancel-lock', 'OrderController@cancelLock'); // 取消锁定

        Route::post('complete', 'OrderController@complete'); // 完成
        Route::post('apply-consult', 'OrderController@applyConsult'); // 申请协商
        Route::post('cancel-consult', 'OrderController@cancelConsult'); // 取消协商
        Route::post('agree-consult', 'OrderController@agreeConsult'); // 同意协商
        Route::post('reject-consult', 'OrderController@rejectConsult'); // 不同意协商
        Route::post('apply-complain', 'OrderController@applyComplain'); // 申请仲裁
        Route::post('cancel-complain', 'OrderController@cancelComplain'); // 取消仲裁
        Route::post('detail', 'OrderController@detail'); // 详情
        Route::post('add-time', 'OrderController@addTime'); // 加时
        Route::post('add-money', 'OrderController@addMoney'); // 加价
        Route::post('update-account-password', 'OrderController@updateAccountPassword'); // 修改账号密码
        Route::post('apply-complete-image', 'OrderController@applyCompleteImage'); // 完成的截图
    });
    Route::post('update', 'OrderController@update'); // 修改订单
    Route::post('place-order', 'OrderController@placeOrder'); // 下单
});
