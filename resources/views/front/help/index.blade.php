<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/front/css/function.css">
    <link rel="stylesheet" href="/front/lib/js/layui/css/layui.css">
    <link rel="stylesheet" href="/front/css/common.css">
    <link rel="stylesheet" href="/front/css/help.css">
    <title>帮助中心</title>
</head>
<body>
<div class="header">
    <div class="logo">
        <img src="/front/images/help-logo.png" alt="">
    </div>
    <div class="nav">
        <ul>
            <li>
                <a href="#">首页</a>
            </li>
            <li>
                <a href="">接单中心</a>
            </li>
            <li class="active">
                <a href="{{ route('help') }}">帮助中心</a>
            </li>
            <li>
                <a href="#">活动中心</a>
            </li>
            <li>
                <a href="{{ route('notice') }}">公告中心</a>
            </li>
            <li>
                <a href="about-us.html">关于我们</a>
            </li>
        </ul>
    </div>
    <div class="user">
        <a href="{{ route('register') }}" class="register">注册</a>
        <a href="{{ route('login') }}" class="login">登录</a>
    </div>
</div>
<div class="main">
    <div class="bg">
        <img src="/front/images/help-banner.jpg" alt="">
    </div>
    <div class="container">
        <div class="layui-tab">
            <ul class="layui-tab-title">
                @forelse($categories as $category)
                    <li class="layui-this">{!! $category->name !!}</li>
                @empty
                @endforelse
            </ul>
            <div class="layui-tab-content">
                @forelse($categories as $category)
                    <div class="layui-tab-item">
                        <div class="layui-collapse" lay-filter="test">
                            @forelse($category->articles as $article)
                                @if($article->status == 1)
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">{!! $article->title !!}</h2>
                                        <div class="layui-colla-content">
                                            {!! $article->content !!}
                                        </div>
                                    </div>
                                @endif
                            @empty
                            @endforelse
                        </div>
                    </div>
                @empty
                @endforelse
            </div>

        </div>
    </div>
</div>
<script src="/front/lib/js/layui/layui.js"></script>
<script src="/js/jquery-1.11.0.min.js"></script>
<script>
    layui.use(['element'], function () {
        var element = layui.element;
    })
</script>
</body>

</html>