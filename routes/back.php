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

