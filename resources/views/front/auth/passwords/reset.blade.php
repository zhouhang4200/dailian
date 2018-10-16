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
    <div class="container" style="padding: 30px 30px 2px 30px" id="captcha-container">
        <div class="title">重置密码</div>
        <form method="POST" action="{{ route('password.reset') }}"  class="layui-form" >
            {!! csrf_field() !!}

            <div class="layui-form-item">
                <label class="layui-form-label"><i class="iconfont icon-youxiang"></i></label>
                <div class="layui-input-block">
                    <input type="text" name="phone" required="" lay-verify="required" placeholder="请输入手机号" value="{{ old('phone') }}"
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

            <div class="layui-form-item" id="captchaBox" style="height: 45px;display: none">
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
        layui.use(['form', 'layedit', 'laydate'], function() {
            var form = layui.form, layer = layui.layer;

            form.on('submit(confirm)', function (data) {

                if (data.field.password != data.field.new_password) {
                    layer.msg('两次输入密码不一至');
                    return false;
                }

                $.post('{{ route('password.reset') }}', {
                    'phone': data.field.phone,
                    'new_password': encrypt(data.field.new_password),
                    'verification_code': data.field.verification_code
                }, function (result) {
                    if (result.code == 0) {
                        layer.confirm('密码修改成功', {
                            btn: ['前往登录', '取消'],
                            btnAlign: 'c'
                        }, function (index, layero) {
                            window.location.href = '{{ route('login') }}'
                        }, function (index) {
                            layer.close();
                        });
                    } else {
                        layer.msg(result.message);
                    }
                }, 'json');
                return false;
            });

        });

    </script>
    @include('front.auth.verification-js')
@endsection