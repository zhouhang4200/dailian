<?php

/*
|--------------------------------------------------------------------------
| 前台路由
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 登录
Route::namespace('Auth')->group(function (){
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    Route::prefix('password')->group(function (){
        Route::get('email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('reset', 'ResetPasswordController@reset');
        Route::get('reset/{token}', 'ResetPasswordController@showResetForm ')->name('password.reset');
    });
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');
});

// 登录后
Route::group(['middleware' => 'auth'], function (){
    Route::get('/home', 'HomeController@index')->name('home');

    // 员工 与 员工分组
    Route::prefix('employee')->group(function (){
        Route::get('/', 'EmployeeController@index')->name('employee'); // 员工列表
        Route::get('create', 'EmployeeController@index')->name('employee.create'); // 员工添加视图
        Route::post('store', 'EmployeeController@store')->name('employee.store'); // 员工保存
        Route::get('edit', 'EmployeeController@edit')->name('employee.edit'); // 员工编辑视图
        Route::post('update', 'EmployeeController@edit')->name('employee.update'); // 员工信息更新
        Route::post('delete', 'EmployeeController@delete')->name('employee.delete'); // 员工删除

        Route::get('group', 'EmployeeController@group')->name('employee.group'); // 员工分组列表
        Route::get('group/create', 'EmployeeController@groupCreate')->name('employee.group.create'); // 员工分组添加视图
        Route::post('group/store', 'EmployeeController@groupStore')->name('employee.group.store'); // 员工分组保存
        Route::get('group/edit', 'EmployeeController@groupEdit')->name('employee.group.edit'); // 员工分组编辑视图
        Route::post('group/update', 'EmployeeController@groupUpdate')->name('employee.group.update'); // 员工分组信息更新
        Route::post('group/delete', 'EmployeeController@groupDelete')->name('employee.group.update'); // 员工分组信息更新
    });
    // 订单
    Route::prefix('order')->group(function (){
        Route::get('/', 'OrderController@index')->name('order');
    });
    // 财务
    Route::prefix('finance')->group(function (){
        Route::get('/', 'FinanceController@index')->name('finance');
    });
    // 个人资料
    Route::prefix('profile')->group(function (){
        Route::get('/', 'ProfileController@index')->name('finance');
    });
});

