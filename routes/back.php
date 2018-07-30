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



// 登录
Route::namespace('Auth')->group(function (){
    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
});


// 登录后首页
Route::group(['middleware' => 'auth.admin'], function (){
    Route::get('/', 'HomeController@index')->name('admin');


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
Route::prefix('finance')->namespace('Finance')->group(function () {
    // 余额提现管理
    Route::prefix('balance-withdraw')->group(function () {
        Route::get('/', 'BalanceWithdrawController@index')->name('admin.balance-withdraw');
        Route::post('agree', 'BalanceWithdrawController@agree')->name('admin.balance-withdraw.agree'); // 同意
        Route::post('refuse', 'BalanceWithdrawController@refuse')->name('admin.balance-withdraw.refuse'); // 拒绝
        Route::get('export', 'BalanceWithdrawController@export')->name('admin.balance-withdraw.export'); // 导出
    });

    // 平台资金日报表
    Route::prefix('platform-finance-report-day')->group(function () {
        Route::get('/', 'PlatformFinanceReportDayController@index')->name('admin.platform-finance-report-day');
        Route::get('export', 'PlatformFinanceReportDayController@export')->name('admin.platform-finance-report-day.export'); // 导出
    });

    // 用户资金日报表
    Route::prefix('user-finance-report-day')->group(function () {
        Route::get('/', 'UserFinanceReportDayController@index')->name('admin.user-finance-report-day');
        Route::get('export', 'UserFinanceReportDayController@export')->name('admin.user-finance-report-day.export'); // 导出
    });

    // 用户资金明细
    Route::prefix('user-asset-flow')->group(function () {
        Route::get('/', 'UserAssetFlowController@index')->name('admin.user-asset-flow');
        Route::get('export', 'UserAssetFlowController@export')->name('admin.user-asset-flow.export'); // 导出
    });
});

// 商户管理
Route::prefix('user')->group(function () {
    // 商户列表
    Route::get('/', 'UserController@index')->name('admin.user');
    Route::get('show/{id}', 'UserController@show')->name('admin.user.show'); // 详情
    Route::get('certification/{id}', 'UserController@certification')->name('admin.user.certification'); // 实名认证信息
    Route::post('certification/pass', 'UserController@certificationPass')->name('admin.user.certification-pass'); // 实名认证通过
    Route::post('certification/refuse', 'UserController@certificationRefuse')->name('admin.user.certification-refuse'); // 实名认证拒绝
    Route::post('close-account', 'UserController@closeAccount')->name('admin.user.close-account'); // 封号
    Route::post('open-account', 'UserController@openAccount')->name('admin.user.open-account'); // 解封
});

// 公告，帮助中心
Route::prefix('article')->group(function () {
    // 公告中心
    Route::prefix('notice')->group(function () {
        Route::get('', 'ArticleController@noticeIndex')->name('admin.article.notice');
        Route::get('create', 'ArticleController@noticeCreate')->name('admin.article.notice-create');
        Route::post('store', 'ArticleController@noticeStore')->name('admin.article.notice-store');
        Route::get('edit/{id}', 'ArticleController@noticeEdit')->name('admin.article.notice-edit');
        Route::post('update', 'ArticleController@noticeUpdate')->name('admin.article.notice-update');
        Route::post('delete', 'ArticleController@noticeDelete')->name('admin.article.notice-delete');
        // 分类
        Route::prefix('category')->group(function () {
            Route::get('/', 'ArticleController@categoryNoticeIndex')->name('admin.article.category-notice');
            Route::get('create', 'ArticleController@categoryNoticeCreate')->name('admin.article.category-notice-create');
            Route::post('store', 'ArticleController@categoryNoticeStore')->name('admin.article.category-notice-store');
            Route::get('edit/{id}', 'ArticleController@categoryNoticeEdit')->name('admin.article.category-notice-edit');
            Route::post('update', 'ArticleController@categoryNoticeUpdate')->name('admin.article.category-notice-update');
            Route::post('delete', 'ArticleController@categoryNoticeDelete')->name('admin.article.category-notice-delete');
        });
    });

    // 帮助中心
    Route::prefix('help')->group(function () {
        Route::get('', 'ArticleController@helpIndex')->name('admin.article.help');
        Route::get('create', 'ArticleController@helpCreate')->name('admin.article.help-create');
        Route::post('store', 'ArticleController@helpStore')->name('admin.article.help-store');
        Route::get('edit/{id}', 'ArticleController@helpEdit')->name('admin.article.help-edit');
        Route::post('update', 'ArticleController@helpUpdate')->name('admin.article.help-update');
        Route::post('delete', 'ArticleController@helpDelete')->name('admin.article.help-delete');
        // 分类
        Route::prefix('category')->group(function () {
            Route::get('/', 'ArticleController@categoryHelpIndex')->name('admin.article.category-help');
            Route::get('create', 'ArticleController@categoryHelpCreate')->name('admin.article.category-help-create');
            Route::post('store', 'ArticleController@categoryHelpStore')->name('admin.article.category-help-store');
            Route::get('edit/{id}', 'ArticleController@categoryHelpEdit')->name('admin.article.category-help-edit');
            Route::post('update', 'ArticleController@categoryHelpUpdate')->name('admin.article.category-help-update');
            Route::post('delete', 'ArticleController@categoryHelpDelete')->name('admin.article.category-help-delete');
        });
    });
});

Route::get('403', function(){
    return view('back.errors.403');
})->name('admin.403');



