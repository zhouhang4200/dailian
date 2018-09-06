<?php

$orderRoute = [
    'order.take-list',
    'order.wait-list',
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
    'real-name-certification',
    'real-name-certification.create',
    'real-name-certification.edit',
    'real-name-certification.store',
    'real-name-certification.update',
];

$financeRoute = [
    'finance.asset-flow',
    'finance.balance-withdraw.record',
    'finance.balance-recharge.record',
    'finance.finance-report-day',
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
    <script src="//cdn.bootcss.com/socket.io/1.3.7/socket.io.min.js"></script>
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
        .layui-layout-admin .layui-logo {
            background-color: #198cff !important;
        }
        .layui-nav-tree .layui-this, .layui-nav-tree .layui-this>a, .layui-nav-tree .layui-nav-child dd.layui-this, .layui-nav-tree .layui-nav-child dd.layui-this a {
            background-color: #198cff !important;
        }
        .layui-nav-tree .layui-nav-bar {
            width: 5px;
            height: 0;
            background-color: #198cff;
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
                    <a href="javascript:" class="message-list">
                        <i class="layui-icon layui-icon-notice"></i>
                        <!-- 如果有新消息，则显示小圆点 -->
                        <span class="layui-badge-dot"></span>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect style="margin-right: 30px;">
                    <a href="javascript:;">
                        <img src="{{ auth()->user()->avatar ?? '' }}" class="layui-nav-img">
                        <cite>{{ optional(auth()->user())->name }}</cite>
                    </a>
                    <dl class="layui-nav-child" id="parent-id" lay-user-id="{{ optional(auth()->user())->parent_id }}">
                        <dd style="text-align: center;">
                            <a href="{{ route('profile') }}">基本资料</a>
                        </dd>
                        <hr/>
                        <dd style="text-align: center;">
                            <a href="javascript:" id="change-password">修改登录密码</a>
                        </dd>
                        <hr>
                        <dd style="text-align: center;">
                            <a href="javascript:" id="change-pay-password">修改支付密码</a>
                        </dd>
                        <hr>
                        <dd style="text-align: center;" id="logout">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();">
                                退出
                            </a>
                        </dd>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
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
                    <p style="height: 25px;line-height: 25px; display: inline-block"></p>
                </div>

                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
                    lay-filter="layadmin-system-side-menu">

                    <li data-name="home"
                        class="layui-nav-item @if(in_array(Route::currentRouteName(), $orderRoute)) layui-nav-itemed @endif">
                        <a href="javascript:;" lay-tips="接单管理" lay-direction="2">
                            <i class="layui-icon iconfont  icon-group-o"></i>
                            <cite>订单管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'order.wait-list') layui-this  @endif" >
                                <a href="{{ route('order.wait-list') }}">接单中心</a>
                            </dd>
                        </dl>
                        @if(Auth::user()->could(['order.take-list']))
                        <dl class="layui-nav-child">
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'order.take-list') layui-this  @endif" >
                                <a href="{{ route('order.take-list') }}">接单管理</a>
                            </dd>
                        </dl>
                        @endif
                    </li>

                    @if(Auth::user()->could(['finance.asset-flow', 'finance.balance-withdraw', 'finance.balance-recharge.record', 'finance.order-statistic']))
                    <li data-name="home" class="layui-nav-item @if(in_array(Route::currentRouteName(), $financeRoute)) layui-nav-itemed @endif">
                        <a href="javascript:;" lay-tips="财务管理" lay-direction="2">
                            <i class="layui-icon iconfont  icon-finance-o"></i>
                            <cite>财务管理</cite>
                        </a>
                        @if(Auth::user()->could('finance.asset-flow'))
                        <dl class="layui-nav-child">
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'finance.finance-report-day') layui-this  @endif" >
                                <a href="{{ route('finance.finance-report-day') }}">资金日报</a>
                            </dd>
                        </dl>
                        <dl class="layui-nav-child">
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'finance.asset-flow') layui-this  @endif" >
                                <a href="{{ route('finance.asset-flow') }}">资金明细</a>
                            </dd>
                        </dl>
                        @endif
                        @if(Auth::user()->could('finance.balance-withdraw'))
                        <dl class="layui-nav-child">
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'finance.balance-withdraw') layui-this  @endif" >
                                <a href="{{ route('finance.balance-withdraw') }}">提现记录</a>
                            </dd>
                        </dl>
                        @endif
                        @if(Auth::user()->could('finance.balance-recharge.record'))
                        <dl class="layui-nav-child">
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'finance.balance-recharge.record') layui-this  @endif" >
                                <a href="{{ route('finance.balance-recharge.record') }}">充值记录</a>
                            </dd>
                        </dl>
                        @endif
                        @if(Auth::user()->could('finance.order-statistic'))
                            <dl class="layui-nav-child">
                                <dd data-name="console"
                                    class="@if(Route::currentRouteName() == 'finance.order-statistic') layui-this  @endif" >
                                    <a href="{{ route('finance.order-statistic') }}">订单统计</a>
                                </dd>
                            </dl>
                        @endif
                    </li>
                    @endif
                    @if(Auth::user()->could(['employee.group', 'employee']) || Auth::user()->isParent())
                    <li data-name="home"
                        class="layui-nav-item @if(in_array(Route::currentRouteName(), $accountRoute)) layui-nav-itemed @endif">
                        <a href="javascript:;" lay-tips="账号管理" lay-direction="2">
                            <i class="layui-icon iconfont  icon-group-o"></i>
                            <cite>账号管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            @if(Auth::user()->isParent())
                            <dd data-name="console"
                                class="@if(in_array(Route::currentRouteName(), ['real-name-certification', 'real-name-certification.create'])) layui-this  @endif">
                                <a href="{{ route('real-name-certification') }}">实名认证</a>
                            </dd>
                            @endif
                            @if(Auth::user()->could('employee.group'))
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'employee.group') layui-this  @endif">
                                <a href="{{ route('employee.group') }}">岗位管理</a>
                            </dd>
                            @endif
                            @if(Auth::user()->could('employee'))
                            <dd data-name="console"
                                class="@if(Route::currentRouteName() == 'employee') layui-this  @endif">
                                <a href="{{ route('employee') }}">员工管理</a>
                            </dd>
                            @endif
                        </dl>
                    </li>
                    @endif
                    <li data-name="home"
                        class="layui-nav-item @if(in_array(Route::currentRouteName(), ['help'])) layui-nav-itemed @endif">
                        <a href="{{ route('help') }}" lay-tips="帮助中心" lay-direction="2">
                            <i class="layui-icon iconfont  icon-finance-o"></i>
                            <cite>帮助中心</cite>
                        </a>

                    </li>

                    <li data-name="home"
                        class="layui-nav-item @if(in_array(Route::currentRouteName(), ['notice'])) layui-nav-itemed @endif">
                        <a href="{{ route('notice') }}" lay-tips="公告中心" lay-direction="2">
                            <i class="layui-icon iconfont  icon-finance-o"></i>
                            <cite>公告中心</cite>
                        </a>
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
<script src="/front/lib/js/layui/layui.js"></script>
<script src="/js/jquery-1.11.0.min.js"></script>
<script src="/js/encrypt.js"></script>
<script src="/front/js/helper.js"></script>
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});

    layui.config({
        base: '/front/' //静态资源所在路径
    }).extend({
        index: 'lib/js/index' //主入口模块
    }).use('index');


    layui.use(['form', 'layedit', 'laydate', 'element'], function(){
        var form = layui.form, layer = layui.layer, element = layui.element, laydate = layui.laydate;

        layer.config({
            isOutAnim: false
        });

        laydate.render({elem: '#start-time'});
        laydate.render({elem: '#end-time'});

        @include('front.profile.password-js')

        $(document).on('click', '.message-list', function () {
            layer.open({
                title:'代练留言',
                type: 2,
                move: false,
                resize:false,
                scrollbar: false,
                area: ['800px', '500px'],
                content: '{{ route('order.operation.message-list') }}'
            });
            return false;
        });

        $(document).on('click', '.cancel', function () {
            layer.closeAll();
        });
        $(document).on('click', '.cancel-current', function () {
            layer.close(layer.index);
        });
    });

    $('#logout').click(function () {
        layer.confirm('确定退出吗?', {icon: 3, title:'提示'}, function(index){
            document.getElementById('logout-form').submit();
            layer.close(index);
            return true;
        });
    });

    (function () {
        var socket=io("{{ env('SOCKET') }}");
        var user_id="{{ auth()->user()->parent_id }}";
        socket.on("blockade:"+user_id, function (message) {
            var message="<div style='padding:25px;font-size:14px; line-height:25px;letter-spacing:1px'>&nbsp;&nbsp;&nbsp;"+message+"</div>";
            layer.open({
                type: 1,
                title:'提示',
                area:'400px'
                ,content: message
                ,btn: '确定'
                ,btnAlign: 'c' //按钮居中
                ,shade: 0 //不显示遮罩
                ,yes: function(){
                    document.getElementById('logout-form').submit();
                    layer.closeAll();
                },
                cancel:function () {
                    document.getElementById('logout-form').submit();
                    layer.closeAll();
                }
            });
        });
    })(window);
</script>
@yield('js')
</body>
@yield('pop')
@include('front.profile.password-pop')
</html>
