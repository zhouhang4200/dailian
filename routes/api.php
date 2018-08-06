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
    Route::prefix('order')->group(function () {
        Route::post('onsale', 'OrderController@onSale')->name('order.onsale'); // 上架
        Route::post('offsale', 'OrderController@offSale')->name('order.offsale'); // 下架
        Route::post('delete', 'OrderController@delete')->name('order.delete'); // 撤单
        Route::post('apply-complete', 'OrderController@applyComplete')->name('order.apply-complete'); // 申请验收
        Route::post('cancel-complete', 'OrderController@cancelComplete')->name('order.cancel-complete'); // 取消验收
        Route::post('complete', 'OrderController@complete')->name('order.complete'); // 完成
        Route::post('lock', 'OrderController@lock')->name('order.lock'); // 锁定
        Route::post('cancel-lock', 'OrderController@cancelLock')->name('order.cancel-lock'); // 取消锁定
        Route::post('anomaly', 'OrderController@anomaly')->name('order.anomaly'); // 异常
        Route::post('cancel-anomaly', 'OrderController@cancelAnomaly')->name('order.cancel-anomaly'); // 取消异常
        Route::post('apply-cancel', 'OrderController@applyConsult')->name('apply-cancel'); // 申请协商
        Route::post('cancel-consult', 'OrderController@cancelConsult')->name('cancel-consult'); // 取消协商
        Route::post('agree-consult', 'OrderController@agreeConsult')->name('agree-consult'); // 同意协商
        Route::post('apply-complain', 'OrderController@applyComplain')->name('apply-complain'); // 申请仲裁
        Route::post('cancel-complain', 'OrderController@cancelComplain')->name('order.cancel-complain'); // 取消仲裁
        Route::post('arbitration', 'OrderController@arbitration')->name('order.arbitration'); // 客服仲裁
    });
});