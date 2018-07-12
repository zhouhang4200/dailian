<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" >
    <link rel="stylesheet" href="/vendor/layui/css/layui.css">
    <link rel="stylesheet" href="/front/css/style.css">
    <link rel="stylesheet" href="/front/css/animate.min.css">
    <link rel="stylesheet" href="/front/css/layui-rewrit.css">
    <link rel="stylesheet" href="/front/css/iconfont.css">
    @yield('css')
</head>
<body>
<!--START 顶部菜单-->
@include('front.layouts.header')
<!--END 顶部菜单-->

<!--START 主体-->
<div class="main">
    <div class="wrapper">
        <div class="left">
            <div class="column-menu">
                @yield('submenu')
            </div>
        </div>

        <div class="right">
            <div class="content">
                <div class="path"><span>@yield('title')</span></div>
                @yield('main')
            </div>
        </div>
    </div>
</div>
<!--END 主体-->

<!--START 底部-->

<!--END 底部-->

<!--START 脚本-->
<script src="/vendor/layui/layui.js"></script>
<script src="/js/jquery-1.11.0.min.js"></script>
<script src="/front/js/helper.js"></script>
<script src="/js/encrypt.js"></script>
@yield('js')
<!--END 脚本-->
</body>
</html>