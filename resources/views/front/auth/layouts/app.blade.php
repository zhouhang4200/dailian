<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/front/lib/js/layui/css/layui.css">
    <link rel="stylesheet" href="/front/lib/css/iconfont.css">
    <link rel="stylesheet" href="/front/lib/css/new.css">
    <link rel="stylesheet" href="/front/css/index.css">
    <title>Document</title>
</head>

<body>
<div class="header">
    <div class="container">
        <img src="/front/images/title.png" alt="">
    </div>
</div>
<div class="main">
    <div class="container">
        <div class="login_img">
            <img src="/front/images/login_img.png" alt="">
        </div>
        <div class="login_box" id='login'>
            <div class="login_title">
                账号登录
            </div>
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-zhanghao-copy"></i></label>
                    <div class="layui-input-block">
                        <input type="text" name="usernumber" autocomplete="off" placeholder="请输入账号" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-ad77"></i></label>
                    <div class="layui-input-block">
                        <input type="password" name="password" autocomplete="off" placeholder="请输入密码" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <img src="/front/images/vcode.png" alt="">
                </div>
                <div class="layui-form-item">
                    <button class="qs-btn" lay-submit="" lay-filter="login">登录</button>
                </div>
                <div class="layui-form-item login-link">
                    <a href="#" style="float: left;">忘记密码</a>
                    <a href="#" class = "register" style="float: right;">立即注册</a>
                </div>
            </form>
        </div>
        <div class="login_box" id="register" style="display: none">
            <div class="login_title">
                立即注册
            </div>
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-zhanghao-copy"></i></label>
                    <div class="layui-input-block">
                        <input type="text" name="usernumber" autocomplete="off" placeholder="请输入账号" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-ad77"></i></label>
                    <div class="layui-input-block">
                        <input type="password" name="password" autocomplete="off" placeholder="请输入密码" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-zhanghao"></i></label>
                    <div class="layui-input-block">
                        <input type="text" name="usernumber"  autocomplete="off" placeholder="请输入昵称" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-youxiang"></i></label>
                    <div class="layui-input-block">
                        <input type="password" name="password" autocomplete="off" placeholder="请输入邮箱" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <img src="/front/images/vcode.png" alt="">
                </div>
                <div class="layui-form-item">
                    <button class="qs-btn" lay-submit="" lay-filter="register">注册</button>
                </div>
                <div class="layui-form-item login-link">
                    <a href="#" class="login" style="float: right;color: #f40;">立即登录</a>
                </div>
            </form>
        </div>
    </div>



</div>
<script src="/front/lib/js/layui/layui.js"></script>
<script src="/front/lib/js/jquery-1.11.0.min.js"></script>
<script>
    layui.use(['element', 'form'], function () {
        var element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
        form = layui.form;
        //监听提交
        form.on('submit(login)', function (data) {
            layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            })
            return false;
        });
        $('.register').click(function () {
            $('#login').css({'display':'none'});
            $('#register').css({'display':'block'})
        })
        $('.login').click(function () {
            $('#register').css({'display':'none'})
            $('#login').css({'display':'block'});
        })
    })
</script>
</body>

</html>