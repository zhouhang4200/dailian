@extends('front.auth.layouts.app')

@section('title', '丸子平台-登录')

@section('css')
    <link rel="stylesheet" href="/front/css/index.css">
@endsection

@section('header')
    <div class="header">
        <div class="container">
            <img src="/front/images/title1.png" alt="">
        </div>
    </div>
@endsection

@section('main')
    <div class="container">
        <div class="login_img">
            <img src="/front/images/login_img.png" alt="">
        </div>
        <div class="login_box" style="height:410px;">
            <div class="login_title">
                账号登录
            </div>
            <form action="" class="layui-form" id="captcha-container">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-dianhua"></i></label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" lay-verify="required" autocomplete="off" placeholder="请输入手机号" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-ad77"></i></label>
                    <div class="layui-input-block">
                        <input type="password" name="password" lay-verify="required" autocomplete="off" placeholder="请输入密码" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item" style="height: 45px;" id="captchaBox">
                    {{--{!! Geetest::render() !!}--}}
                </div>
                <div class="layui-form-item">
                    <button class="qs-btn" lay-submit="" lay-filter="login">登录</button>
                </div>
                <div class="layui-form-item login-link" style="margin-bottom: 20px">
                    <a href="{{ route('password.reset') }}" class = "login" style="float: right;">忘记密码</a>
                    <a href="{{ route('register') }}" style="float: left;">立即注册</a>
                </div>
            </form>
            <div style="width: 410px;height: 130px;margin-top:5px;position: absolute;right:-10px;background: #342353"></div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://static.geetest.com/static/tools/gt.js"></script>
    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
        layui.use(['form', 'layedit', 'laydate', 'element'], function(){
            var form = layui.form
            ,layer = layui.layer;

            form.on('submit(login)', function (data) {
            $.post('{{ route('login') }}', {
                phone:data.field.phone,
                password:encrypt(data.field.password),
                geetest_challenge:data.field.geetest_challenge,
                geetest_seccode:data.field.geetest_seccode,
                geetest_validate:data.field.geetest_validate
            }, function (result) {
                if (result.status == 1) {
                    location.reload();
                } else if (result.status == 3){
                    var message="<div style='padding:25px;font-size:14px; line-height:25px;letter-spacing:1px'>&nbsp;&nbsp;&nbsp;"+result.message+"</div>";
                    layer.open({
                        type: 1,
                        title:'提示',
                        area:'400px'
                        ,content: message
                        ,btn: '确定'
                        ,btnAlign: 'c' //按钮居中
                        ,shade: 0 //不显示遮罩
                        ,yes: function(){
                            layer.closeAll();
                        }
                    });
                } else {
                    layer.msg(result.message);
                }
            return false;
            });
            return false;
        });

            $('body').height($(window).height());
        });
        $.ajax({
            url: "{{ Config::get('geetest.url', 'geetest') }}?t=" + (new Date()).getTime(),
            type: "get",
            dataType: "json",
            success: function (data) {
                //请检测data的数据结构， 保证data.gt, data.challenge, data.success有值
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    offline: !data.success,
                    new_captcha: true,
                    product: 'custom',
                    area: '#captcha-container',
                    next_width: '100%',
                    bg_color: 'black',
                    lang: '{{ Config::get('geetest.lang', 'zh-cn') }}',
                    http: '{{ Config::get('geetest.protocol', 'http') }}' + '://'
                }, function (captchaObj) {
                    captchaObj.appendTo("#captchaBox");
                    captchaObj.onReady(function () {
                    }).onSuccess(function () {
                    }).onError(function () {
                    })
                });
            }
        });
    </script>
@endsection
