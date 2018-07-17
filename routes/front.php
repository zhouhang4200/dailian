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

Route::get('/', function (){
    return view('welcome');
});

// 登录后
Route::group(['middleware' => 'auth'], function (){
    Route::get('/home', 'HomeController@index')->name('home');

    // 员工 与 岗位
    Route::prefix('employee')->group(function (){
        Route::get('/', 'EmployeeController@index')->name('employee'); // 员工列表
        Route::get('create', 'EmployeeController@create')->name('employee.create'); // 员工添加视图
        Route::post('store', 'EmployeeController@store')->name('employee.store'); // 员工保存
        Route::get('edit/{id}', 'EmployeeController@edit')->name('employee.edit'); // 员工编辑视图
        Route::post('update', 'EmployeeController@update')->name('employee.update'); // 员工信息更新
        Route::post('delete', 'EmployeeController@delete')->name('employee.delete'); // 员工删除
        Route::post('forbidden', 'EmployeeController@forbidden')->name('employee.forbidden'); // 员工删除

        Route::get('group', 'EmployeeController@group')->name('employee.group'); // 岗位列表
        Route::get('group/create', 'EmployeeController@groupCreate')->name('employee.group.create'); // 岗位添加视图
        Route::post('group/store', 'EmployeeController@groupStore')->name('employee.group.store'); // 岗位保存
        Route::get('group/edit/{id}', 'EmployeeController@groupEdit')->name('employee.group.edit'); // 岗位编辑视图
        Route::post('group/update', 'EmployeeController@groupUpdate')->name('employee.group.update'); // 岗位信息更新
        Route::post('group/delete', 'EmployeeController@groupDelete')->name('employee.group.delete'); // 岗位信息更新
    });
    // 订单
    Route::prefix('order')->group(function (){
        Route::get('/', 'OrderController@index')->name('order'); // 待接单列表
        Route::get('take', 'OrderController@take')->name('order.take'); // 接单管理视图
        Route::post('take', 'OrderController@takeData'); // 接单列表数据
        Route::get('take/{tradeNO?}', 'OrderController@takeShow'); // 接单方查看订单详情

        Route::get('send', 'OrderController@send')->name('order.send'); // 发单管理视图
        Route::post('send', 'OrderController@sendData'); // 发单列表数据

        Route::prefix('operation')->group(function (){ // 订单操作
            Route::post('take', 'OrderOperationController@takeData')->name('order.operation.take'); // 接单
            Route::post('apply-complete', 'OrderOperationController@applyComplete')->name('order.operation.apply-complete'); // 申请验收
            Route::post('cancel-complete', 'OrderOperationController@cancelComplete')->name('order.operation.cancel-complete'); // 取消验收
            Route::post('complete', 'OrderOperationController@complete')->name('order.operation.complete'); // 完成
            Route::post('on-sale', 'OrderOperationController@onSale')->name('order.operation.on-sale'); // 上架
            Route::post('off-sale', 'OrderOperationController@offSale')->name('order.operation.off-sale'); // 下架
            Route::post('lock', 'OrderOperationController@lock')->name('order.operation.lock'); // 锁定
            Route::post('cancel-lock', 'OrderOperationController@cancelLock')->name('order.operation.cancel-lock'); // 取消锁定
            Route::post('anomaly', 'OrderOperationController@anomaly')->name('order.operation.anomaly'); // 异常
            Route::post('cancel-anomaly', 'OrderOperationController@cancelAnomaly')->name('order.operation.cancel-anomaly'); // 取消异常
            Route::post('apply-consult', 'OrderOperationController@applyConsult')->name('order.operation.apply-consult'); // 申请撤销
            Route::post('cancel-consult', 'OrderOperationController@cancelConsult')->name('order.operation.cancel-consult'); // 取消撤销
            Route::post('agree-consult', 'OrderOperationController@agreeConsult')->name('order.operation.agree-consult'); // 申请撤销
            Route::post('apply-complain', 'OrderOperationController@applyComplain')->name('order.operation.apply-complain'); // 取消撤销
            Route::post('cancel-complain', 'OrderOperationController@cancelComplain')->name('order.operation.cancel-complain'); // 取消仲裁
        });

    });
    // 财务
    Route::prefix('finance')->group(function (){
        Route::get('/', 'FinanceController@index')->name('finance');
    });
    // 个人资料
    Route::prefix('profile')->group(function (){
        Route::get('/', 'ProfileController@index')->name('profile');
    });
});

// 登录
Route::namespace('Auth')->group(function (){
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    Route::prefix('password')->group(function (){
        Route::post('email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('reset', 'ResetPasswordController@reset');
        Route::get('reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    });
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');
});
