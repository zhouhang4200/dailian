<?php
$userPermissions = [];
$homeRoute = [
    'home-punishes.index',
    'frontend.index',
];

$workbenchRoute = [
    'frontend.workbench.index',
    'frontend.workbench.leveling.wait',
    'frontend.workbench.leveling.create',
    'frontend.workbench.leveling.index',
    'frontend.workbench.leveling.complaints',
];

$accountRoute = [
    'employee',
    'employee.create',
    'employee.edit',
    'employee.update',
    'employee.delete',
    'employee.forbidden',
    'employee.group',
    'employee.group',
    'employee.group.create',
    'employee.group.store',
    'employee.group.edit',
    'employee.group.edit',
    'employee.group.update',
];

$financeRoute = [
    'finance',
];

$settingRoute = [
    'frontend.setting.receiving-control.index',
    'frontend.setting.api-risk-management.index',
    'frontend.setting.skin.index',
    'frontend.setting.automatically-grab.goods',
    'frontend.setting.sms.index',
    'frontend.setting.tb-auth.index',
    'frontend.setting.sending-assist.auto-markup',
    'frontend.setting.tb-auth.store',
    'frontend.setting.order-send-channel.index',
];

$goodsRoute = [
    'frontend.goods.index',
];

$myAccount = ['home-accounts.edit', 'home-accounts.index'];
$stationManagement = ['station.create', 'station.index', 'station.edit'];
$employeeManagement = ['staff-management.index', 'staff-management.edit', 'staff-management.create'];
$blacklist = ['hatchet-man-blacklist.index', 'hatchet-man-blacklist.create', 'hatchet-man-blacklist.edit'];
$finance = ['frontend.finance.asset', 'frontend.finance.amount-flow', 'frontend.finance.asset-daily',
        'frontend.finance.withdraw-order', 'frontend.finance.order-report.index', 'frontend.statistic.employee',
        'frontend.statistic.order', 'frontend.statistic.sms'
];
?>
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="_token" content="{{ csrf_token() }}" >
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/front/lib/js/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/front/lib/css/admin.css" media="all">
    <link rel="stylesheet" href="/front/lib/css/new.css">
    <link id="layuicss-layer" rel="stylesheet" href="/front/lib/js/layui/css/modules/layer/default/layer.css" media="all">
    <style>
        .layui-layout-admin .layui-body {
            top: 50px;
        }

        .layui-layout-admin .layui-footer {
            height: 52px;
        }

        .footer {
            height: 72px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .main {
            padding: 20px;
        }

        .layui-footer {
            z-index: 999;
        }

        .layui-card-header {
            height: auto;
        }

        .layui-card .layui-tab {
            margin: 10px 0;
        }
        .layui-form-item {
            margin-bottom: 12px;
        }
        .layui-tab-title li{
            min-width: 50px;
        }
        .qsdate{
            display: inline-block;
            width: 42%;
        }
        /* 改写header高度 */
        .layui-card-header {
            height: 56px;
            line-height: 32px;
            color: #303133;
            font-size: 14px;
        }
        .layui-side-menu .layui-nav .layui-nav-item .layui-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            margin-top: -20px;
            font-size: 24px;
        }
        .layui-card-body {
            padding-bottom: 30px;
        }
    </style>
    @yield('css')
</head>

<body class="layui-layout-body">

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

                <li class="layui-nav-item" lay-unselect>
                    <a  id="leveling-message">
                        <i class="layui-icon layui-icon-notice"></i>
                        <!-- 如果有新消息，则显示小圆点 -->
                        <span class="layui-badge-dot"></span>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect style="margin-right: 30px;">
                    <a href="javascript:;">
                        <img src="{{ auth()->user()->voucher ?? '' }}" class="layui-nav-img">
                        <cite>{{ auth()->user()->username }}</cite>
                    </a>
                    <dl class="layui-nav-child">
                        <dd style="text-align: center;">
                            <a href="{{ route('home') }}">基本资料</a>
                        </dd>
                        <hr>
                        <dd style="text-align: center;">
                            <a href="#" id="logout">退出</a>
                        </dd>
                    </dl>
                </li>

                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                    <a href="javascript:;" layadmin-event="more">
                        <i class="layui-icon layui-icon-more-vertical"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="">
                    <img src="/front/images/title.png" alt="">
                </div>

                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
                    lay-filter="layadmin-system-side-menu">

                    <li data-name="home"
                        class="layui-nav-item @if(in_array(Route::currentRouteName(), $accountRoute)) layui-nav-itemed @endif">
                        <a href="{{ route('order.take') }}" lay-tips="账号" lay-direction="2">
                            <i class="layui-icon iconfont  icon-group-o"></i>
                            <cite>接单管理</cite>
                        </a>
                    </li>

                    <li data-name="home"
                        class="layui-nav-item @if(in_array(Route::currentRouteName(), $financeRoute)) layui-nav-itemed @endif">
                        <a href="javascript:;" lay-tips="账号" lay-direction="2">
                            <i class="layui-icon iconfont  icon-group-o"></i>
                            <cite>财务管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'finance') layui-this  @endif" >
                                <a href="{{ route('finance') }}">资金流水</a>
                            </dd>
                        </dl>
                    </li>

                    <li data-name="home"
                        class="layui-nav-item @if(in_array(Route::currentRouteName(), $accountRoute)) layui-nav-itemed @endif">
                        <a href="javascript:;" lay-tips="账号" lay-direction="2">
                            <i class="layui-icon iconfont  icon-group-o"></i>
                            <cite>账号管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd data-name="console"
                                class="@if( in_array(Route::currentRouteName(), $myAccount))   layui-this  @endif" >
                                <a href="">我的账号</a>
                            </dd>
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'employee.group') layui-this  @endif">
                                <a href="{{ route('employee.group') }}">岗位管理</a>
                            </dd>
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'employee') layui-this  @endif">
                                <a href="{{ route('employee') }}">员工管理</a>
                            </dd>
                        </dl>
                    </li>

                </ul>
            </div>
        </div>
        <!-- 主体内容 -->
        <div class="layui-body">
            <div class="layadmin-tabsbody-item layui-show">
                <div class="layui-fluid">
                    <div class="layui-row layui-col-space15">
                        @yield('main')
                    </div>
                </div>
            </div>
        </div>
        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>

<style>
    .layui-side-menu,
    .layadmin-pagetabs .layui-tab-title li:after,
    .layadmin-pagetabs .layui-tab-title li.layui-this:after,
    .layui-layer-admin .layui-layer-title,
    .layadmin-side-shrink .layui-side-menu .layui-nav>.layui-nav-item>.layui-nav-child {
        background-color: #20222A !important;
    }

    .layui-nav-tree .layui-this,
    .layui-nav-tree .layui-this>a,
    .layui-nav-tree .layui-nav-child dd.layui-this,
    .layui-nav-tree .layui-nav-child dd.layui-this a {
        background-color: #F78400 !important;
    }
    .layui-layout-admin .layui-logo {
        background-color: #F78400 !important;
    }
</style>
<script src="/front/lib/js/layui/layui.js"></script>
<script src="/js/jquery-1.11.0.min.js"></script>
<script src="/js/encrypt.js"></script>
<script src="/front/js/helper.js"></script>
<script src="//cdn.bootcss.com/socket.io/1.3.7/socket.io.min.js"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
</script>
@yield('js')
</body>

@yield('pop')
</html>
