<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/front/lib/js/layui/css/layui.css">
    <link rel="stylesheet" href="/front/lib/css/iconfont.css">
    <link rel="stylesheet" href="/front/lib/css/new-iconfont.css">
    <link rel="stylesheet" href="/front/lib/css/new.css">
    @yield('css')
</head>
<body>
    @yield('header')
    <div class="main">
    @yield('main')
    </div>
    <script src="/front/lib/js/layui/layui.js"></script>
    <script src="/front/lib/js/jquery-1.11.0.min.js"></script>
    <script src="/js/encrypt.js"></script>
    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
    </script>
    @yield('js')
</body>
</html>