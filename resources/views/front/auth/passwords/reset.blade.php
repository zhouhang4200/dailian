@extends('front.auth.layouts.app')

@section('title', '丸子平台-重置登录密码')

@section('css')
    <link rel="stylesheet" href="/front/lib/css/email.css">
@section('header')
    <div class="header">
        <img src="/front/images/title1.png" alt="">
    </div>
@endsection

@section('main')
    <div class="container" style="padding: 30px 30px 2px 30px">
        <div class="title">重置密码</div>
        <form method="POST" action="{{ route('password.reset') }}"  class="layui-form" >
            {!! csrf_field() !!}

            <div class="layui-form-item">
                <label class="layui-form-label"><i class="iconfont icon-youxiang"></i></label>
                <div class="layui-input-block">
                    <input type="text" name="phone" required="" lay-verify="required" placeholder="请输入邮箱" value="{{ old('phone') }}"
                       autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layui-form-label"><i class="iconfont icon-youxiang"></i></label>
                        <input type="text" name="verification_code"  lay-verify="required" placeholder="手机验证码" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <button class="layui-btn layui-btn-normal get-verification send-code-btn" style="width: 100%">获取验证码</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"><i class="iconfont icon-ad77"></i></label>
                <div class="layui-input-block">
                    <input type="password" name="password" required="" lay-verify="required" placeholder="6-22位新密码"
                       autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="iconfont icon-ad77"></i></label>
                <div class="layui-input-block">
                    <input type="password" name="new_password" required="" lay-verify="required" placeholder="确认新密码"
                       autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="confirm" style="width: 100%">确认</button>
            </div>
        </form>
        <div class="layui-form-item login-link" style="margin-top: 10px">
            <a href="{{  route('login') }}" class="login" style="float: left;">立即登录</a>
        </div>
    </div>
@endsection

@section('js')
    <script>
        layui.use(['form', 'layedit', 'laydate'], function(){
            var form = layui.form, layer = layui.layer;

            form.on('submit(confirm)', function (data) {

                if(data.field.password != data.field.new_password) {
                    layer.msg('两次输入密码不一至');
                    return false;
                }

               $.post('{{ route('password.reset') }}', {
                   'phone': data.field.phone,
                   'new_password': data.field.new_password,
                   'verification_code': data.field.verification_code
               }, function (result) {
                    if (result.code == 0) {
                        layer.confirm('密码修改成功', {
                            btn: ['前往登录', '取消'],
                            btnAlign: 'c'
                        }, function(index, layero){
                            window.location.href = '{{ route('login') }}'
                        }, function(index){
                            layer.close();
                        });
                    } else {
                        layer.msg(result.message);
                    }
               }, 'json');
               return false;
            });

            $('.get-verification').click(function () {
                var phone = $('input[name="phone"]').val();

                if ($('input[name="phone"]').val() == '' || ! phone.match(/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/)) {
                    layer.msg('请输入正确手机号')
                } else {
                    setTime(phone);
                }
                return false
            });

            var countdown = 59;
            function setTime(phone) {
                if (countdown == 59) {
                    $.post('{{ route('password.reset.verification-code') }}',  {phone:phone, type:2}, function(result){
                        if (result.code != 0) {
                            return layer.msg(result.message);
                        } else {
                            return layer.msg('验证码发送成功请注意查收');
                        }
                    }, 'json');
                }
                if (countdown == '-1') {
                    $('.send-code-btn').addClass("get-verification");
                    $('.send-code-btn').css("background-color", "");
                    $('.send-code-btn').text("重新发送");
                    countdown = 59;
                } else {

                    $('.send-code-btn').removeClass("get-verification");
                    $('.send-code-btn').css("background-color", "#bbbbbb");
                    $('.send-code-btn').text("重新发送" + countdown);
                    countdown--;
                    setTimeout(function () {
                        setTime()
                    }, 1000);//1s后执行setTime 函数；
                }
            }

        });
    </script>
@endsection