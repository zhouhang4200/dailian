<?php

/*
|--------------------------------------------------------------------------
| 运营后台路由
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 登录后首页
Route::group(['middleware' => 'auth.admin'], function (){
    Route::get('/', 'HomeController@index')->name('home');
});

// 登录
Route::namespace('Auth')->group(function (){
    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
});

// 订单
Route::prefix('order')->group(function(){
    Route::get('/', 'OrderController@index')->name('admin.order');
    Route::get('show/{trade_no}', 'OrderController@show')->name('admin.order.show');
    Route::get('log/{trade_no}', 'OrderController@log')->name('admin.order.log');
});

// 游戏
Route::prefix('game')->group(function(){
    Route::get('/', 'GameController@index')->name('admin.game');
});

// 游戏区
Route::prefix('region')->group(function(){
    Route::get('/', 'RegionController@index')->name('admin.region');
});

// 游戏服务器
Route::prefix('server')->group(function(){
    Route::get('/', 'ServerController@index')->name('admin.server');
});

// 财务
Route::prefix('finance')->group(function () {
    // 余额提现管理
    Route::prefix('balance-withdraw')->group(function () {
        Route::get('/', 'BalanceWithdrawController@index')->name('admin.balance-withdraw');
        Route::post('agree', 'BalanceWithdrawController@agree')->name('admin.balance-withdraw.agree'); // 同意
        Route::post('refuse', 'BalanceWithdrawController@refuse')->name('admin.balance-withdraw.refuse'); // 拒绝
        Route::get('export', 'BalanceWithdrawController@export')->name('admin.balance-withdraw.export'); // 导出
    });
});