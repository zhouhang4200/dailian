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

// 首页
Route::get('/', 'HomeController@index')->name('home')->middleware('syncESOrderStatus');
// 待接单列表
Route::get('order', 'OrderController@index')->name('order');
Route::get('order/detail/{trade_no?}', 'OrderController@indexDetail')->name('order.detail');
Route::post('order/get-server', 'OrderController@gameServers')->name('order.get-server');
// 公告中心
Route::get('notice', 'NoticeController@index')->name('notice');
// 活动中心
Route::get('activity', 'ActivityController@index')->name('activity');
// 帮助
Route::get('help', 'HelpController@index')->name('help');
// 关于我们
Route::get('about-us', 'AboutUsController@index')->name('about-us');
// 小程序开通消息服务token验证
Route::get('mini-program-token-check', 'HomeController@miniProgramTokenCheck')->name('mini-program-token-check');

// 登录后
Route::group(['middleware' => 'auth'], function (){

    // 员工 与 岗位
    Route::prefix('employee')->group(function (){
        Route::get('/', 'EmployeeController@index')->name('employee')->middleware('permission:employee');// 员工列表
        Route::get('create', 'EmployeeController@create')->name('employee.create'); // 员工添加视图
        Route::post('store', 'EmployeeController@store')->name('employee.store'); // 员工保存
        Route::get('edit/{id}', 'EmployeeController@edit')->name('employee.edit'); // 员工编辑视图
        Route::post('update', 'EmployeeController@update')->name('employee.update'); // 员工信息更新
        Route::post('delete', 'EmployeeController@delete')->name('employee.delete'); // 员工删除
        Route::post('forbidden', 'EmployeeController@forbidden')->name('employee.forbidden'); // 员工删除

        Route::get('group', 'EmployeeController@group')->name('employee.group')->middleware('permission:employee.group');// 岗位列表
        Route::get('group/create', 'EmployeeController@groupCreate')->name('employee.group.create'); // 岗位添加视图
        Route::post('group/store', 'EmployeeController@groupStore')->name('employee.group.store'); // 岗位保存
        Route::get('group/edit/{id}', 'EmployeeController@groupEdit')->name('employee.group.edit'); // 岗位编辑视图
        Route::post('group/update', 'EmployeeController@groupUpdate')->name('employee.group.update'); // 岗位信息更新
        Route::post('group/delete', 'EmployeeController@groupDelete')->name('employee.group.delete'); // 岗位信息更新
    });

    # 游戏 区 服 代练类型
    Route::prefix('game')->group(function (){
        Route::post('regions', 'GameController@regions')->name('game.regions');
        Route::post('servers', 'GameController@servers')->name('game.servers');
        Route::post('leveling-type', 'GameController@levelingTypes')->name('game.leveling-types');
    });

    // 订单
    Route::prefix('order')->group(function (){
        // 待接订单
        Route::get('wait-list', 'OrderController@waitList')->name('order.wait-list');
        // 接单列表
        Route::get('take-list', 'OrderController@takeList')->name('order.take-list')->middleware('permission:order.take-list');
        Route::post('take-list', 'OrderController@takeListData')->name('order.take-list');
        Route::get('take-show/{trade_no?}', 'OrderController@takeShow')->name('order.take-show');
        // 发单列表
        Route::get('create', 'OrderController@create')->name('order.create');
        Route::post('store', 'OrderController@store')->name('order.store');
        Route::get('send-list', 'OrderController@sendList')->name('order.send-list');
        Route::post('send-list', 'OrderController@sendListData')->name('order.send-list');
        Route::get('send-show/{trade_no?}', 'OrderController@sendShow')->name('order.send-show');


        // 订单操作
        Route::prefix('operation')->middleware('syncESOrderStatus')->group(function (){
            # 接单
            Route::post('take', 'OrderOperationController@take')->name('order.operation.take');
            # 撤单
            Route::post('delete', 'OrderOperationController@delete')->name('order.operation.delete');
            # 申请验收
            Route::post('apply-complete', 'OrderOperationController@applyComplete')->name('order.operation.apply-complete');
            # 取消验收
            Route::post('cancel-complete', 'OrderOperationController@cancelComplete')->name('order.operation.cancel-complete');
            # 完成
            Route::post('complete', 'OrderOperationController@complete')->name('order.operation.complete');
            # 上架
            Route::post('on-sale', 'OrderOperationController@onSale')->name('order.operation.on-sale');
            # 下架
            Route::post('off-sale', 'OrderOperationController@offSale')->name('order.operation.off-sale');
            # 锁定
            Route::post('lock', 'OrderOperationController@lock')->name('order.operation.lock');
            # 取消锁定
            Route::post('cancel-lock', 'OrderOperationController@cancelLock')->name('order.operation.cancel-lock');
            # 提交异常
            Route::post('anomaly', 'OrderOperationController@anomaly')->name('order.operation.anomaly');
            # 取消异常
            Route::post('cancel-anomaly', 'OrderOperationController@cancelAnomaly')->name('order.operation.cancel-anomaly');
            # 申请撤销
            Route::post('apply-consult', 'OrderOperationController@applyConsult')->name('order.operation.apply-consult');
            # 取消撤销
            Route::post('cancel-consult', 'OrderOperationController@cancelConsult')->name('order.operation.cancel-consult');
            # 同意撤销
            Route::post('agree-consult', 'OrderOperationController@agreeConsult')->name('order.operation.agree-consult');
            # 不同意撤销
            Route::post('reject-consult', 'OrderOperationController@rejectConsult')->name('order.operation.reject-consult');
            # 申请仲裁
            Route::post('apply-complain', 'OrderOperationController@applyComplain')->name('order.operation.apply-complain');
            # 取消仲裁
            Route::post('cancel-complain', 'OrderOperationController@cancelComplain')->name('order.operation.cancel-complain');
            # 申请验收图片
            Route::get('apply-complete-image/{trade_no}', 'OrderOperationController@applyCompleteImage')->name('order.operation.apply-complete-image');
            # 订单操作日志
            Route::get('log/{trade_no}', 'OrderOperationController@log')->name('order.operation.log');
            # 仲裁信息
            Route::get('complain-info/{trade_no}', 'OrderOperationController@complainInfo')->name('order.operation.complain-info');
            # 发送仲裁留言
            Route::post('send-complain-message', 'OrderOperationController@sendComplainMessage')->name('order.operation.send-complain-message');
            # 获取订单留言
            Route::get('message/{trade_no}', 'OrderOperationController@message')->name('order.operation.message');
            # 发送订单留言
            Route::post('send-message/{trade_no}', 'OrderOperationController@sendMessage')->name('order.operation.send-message');
            # 留言列表
            Route::get('message-list', 'OrderOperationController@messageList')->name('order.operation.message-list');
            # 删除留言
            Route::post('delete-message', 'OrderOperationController@deleteMessage')->name('order.operation.delete-message');
            # 删除所有留言
            Route::post('delete-all-message', 'OrderOperationController@deleteAllMessage')->name('order.operation.delete-all-message');
        });
    });

    // 财务
    Route::prefix('finance')->namespace('Finance')->group(function (){
        // 资金流水
        Route::prefix('asset-flow')->group(function (){
            Route::get('/', 'AssetFlowController@index')->name('finance.asset-flow')->middleware('permission:finance.asset-flow');
            Route::get('export', 'AssetFlowController@export')->name('finance.asset-flow.export');
        });
        // 余额充值
        Route::prefix('balance-recharge')->group(function (){
            Route::get('/', 'BalanceRechargeController@index')->name('finance.balance-recharge');
            Route::get('record', 'BalanceRechargeController@record')->name('finance.balance-recharge.record')->middleware('permission:finance.balance-recharge.record');
            Route::get('pay', 'BalanceRechargeController@pay')->name('finance.balance-recharge.pay');
            Route::get('pay-success', 'BalanceRechargeController@paySuccess')->name('finance.balance-recharge.pay-success');
            Route::get('export', 'BalanceRechargeController@export')->name('finance.balance-recharge.export');
        });
        // 余额提现
        Route::prefix('balance-withdraw')->group(function (){
            Route::get('/', 'BalanceWithdrawController@index')->name('finance.balance-withdraw')->middleware('permission:finance.balance-withdraw');
            Route::post('/', 'BalanceWithdrawController@store');
            Route::get('record', 'BalanceWithdrawController@record')->name('finance.balance-withdraw.record');
            Route::get('export', 'BalanceWithdrawController@export')->name('finance.balance-withdraw.export');
        });
        // 资金日报表
        Route::prefix('finance-report-day')->group(function (){
            Route::get('/', 'FinanceReportDayController@index')->name('finance.finance-report-day')->middleware('permission:finance.finance-report-day');
            Route::get('export', 'FinanceReportDayController@export')->name('finance.finance-report-day.export');
        });
        // 订单统计
        Route::prefix('order-statistic')->group(function (){
            Route::get('/', 'OrderStatisticController@index')->name('finance.order-statistic')->middleware('permission:finance.order-statistic');
            Route::get('export', 'OrderStatisticController@export')->name('finance.order-statistic.export');
        });
    });

    // 个人资料
    Route::prefix('profile')->group(function (){
        Route::get('/', 'ProfileController@index')->name('profile');
        Route::get('edit', 'ProfileController@edit')->name('profile.edit');
        Route::post('update', 'ProfileController@update')->name('profile.update');
        Route::post('avatar/show', 'ProfileController@avatarShow')->name('profile.avatar-show'); // layui后台返回头像路径到页面
        Route::post('avatar/update', 'ProfileController@avatarUpdate')->name('profile.avatar-update'); // 上传头像
        Route::post('change-password', 'ProfileController@changePassword')->name('profile.change-password'); // 修改密码
        Route::post('set-pay-password', 'ProfileController@setPayPassword')->name('profile.set-pay-password'); // 设置支付密码
        Route::post('change-pay-password', 'ProfileController@changePayPassword')->name('profile.change-pay-password'); // 修改支付密码
        Route::post('reset-pay-password', 'ProfileController@resetPayPassword')->name('profile.reset-pay-password'); // 重置密码
        Route::post('reset-pay-password-verification-code', 'ProfileController@resetPayPasswordVerificationCode')->name('profile.reset-pay-password-verification-code'); // 获取重置密码验证码
    });
    // 实名认证
    Route::prefix('real-name-certification')->group(function () {
        Route::get('/', 'RealNameCertificationController@index')->name('real-name-certification');
        Route::get('create', 'RealNameCertificationController@create')->name('real-name-certification.create');
        Route::post('store', 'RealNameCertificationController@store')->name('real-name-certification.store');
        Route::get('edit', 'RealNameCertificationController@edit')->name('real-name-certification.edit');
        Route::post('update', 'RealNameCertificationController@update')->name('real-name-certification.update');
        Route::post('image/update', 'RealNameCertificationController@imageUpdate')->name('real-name-certification.image-update');
    });
});

// 余额充值回调
Route::prefix('finance')->namespace('Finance')->group(function () {
    // 余额充值
    Route::prefix('balance-recharge')->group(function () {
        Route::any('alipay-notify', 'BalanceRechargeController@alipayNotify')->name('finance.balance-recharge.alipay-notify');
        Route::any('wechat-notify', 'BalanceRechargeController@wechatNotify')->name('finance.balance-recharge.wechat-notify');
    });
});

// 登录 与 注册
Route::namespace('Auth')->group(function (){
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
    // 密码找回
    Route::prefix('password')->group(function (){
        Route::post('email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('reset', 'ForgotPasswordController@showResetForm')->name('password.reset');
        Route::post('reset', 'ResetPasswordController@reset')->name('password.reset');
//        Route::get('reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    });
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');
    // 获取验证码
    Route::post('verification-code', 'ResetPasswordController@verificationCode')->name('password.reset.verification-code');
});
