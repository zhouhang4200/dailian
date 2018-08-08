<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/front/css/function.css">
    <link rel="stylesheet" href="/front/lib/js/layui/css/layui.css">
    <link rel="stylesheet" href="/front/css/common.css">
    <link rel="stylesheet" href="/front/css/iconfont.css">
    @yield('css')
    <title>丸子代练 @yield('title')</title>
</head>

<body>
<div class="header">
    <div class="logo">
        <img src="/front/images/logo.png" alt="">
    </div>
    <div class="nav">
        <ul>
            <li @if(Route::currentRouteName() == 'home') class="active" @endif>
                <a href="{{ route('home') }}">首页</a>
            </li>
            <li @if(Route::currentRouteName() == 'order') class="active" @endif>
                <a href="{{ route('order') }}">接单中心</a>
            </li>
            <li @if(Route::currentRouteName() == 'help') class="active" @endif>
                <a href="{{ route('help') }}">帮助中心</a>
            </li>
            <li @if(Route::currentRouteName() == 'activity') class="active" @endif>
                <a href="{{ route('activity') }}">活动中心</a>
            </li>
            <li @if(Route::currentRouteName() == 'notice') class="active" @endif>
                <a href="{{ route('notice') }}">公告中心</a>
            </li>
            <li @if(Route::currentRouteName() == 'about-us') class="active" @endif>
                <a href="{{ route('about-us') }}">关于我们</a>
            </li>
        </ul>
    </div>
    <div class="user">
        <a href="{{ route('register') }}" class="register">注册</a>
        <a href="{{ route('login') }}" class="login">登录</a>
    </div>
</div>
@yield('main')
<script src="/front/lib/js/layui/layui.js"></script>
<script src="/js/jquery-1.11.0.min.js"></script>
<script>
    layui.use(['element'], function () {
        var element = layui.element;
    })
</script>
@yield('js')
</body>
</html>