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
        Route::post('onsale', 'OrderController@onSale')->name('order.onsale'); // 上架
        Route::post('offsale', 'OrderController@offSale')->name('order.offsale'); // 下架
        Route::post('delete', 'OrderController@delete')->name('order.delete'); // 撤单
        Route::post('lock', 'OrderController@lock')->name('order.lock'); // 锁定
        Route::post('cancel-lock', 'OrderController@cancelLock')->name('order.cancel-lock'); // 取消锁定

        Route::post('complete', 'OrderController@complete')->name('order.complete'); // 完成
        Route::post('apply-consult', 'OrderController@applyConsult')->name('order.apply-consult'); // 申请协商
        Route::post('cancel-consult', 'OrderController@cancelConsult')->name('order.cancel-consult'); // 取消协商
        Route::post('agree-consult', 'OrderController@agreeConsult')->name('order.agree-consult'); // 同意协商
        Route::post('refuse-consult', 'OrderController@refuseConsult')->name('order.refuse-consult'); // 不同意协商
        Route::post('apply-complain', 'OrderController@applyComplain')->name('order.apply-complain'); // 申请仲裁
        Route::post('cancel-complain', 'OrderController@cancelComplain')->name('order.cancel-complain'); // 取消仲裁
    });
    Route::post('place-order', 'OrderController@placeOrder')->name('order.place-order'); // 下单
});
