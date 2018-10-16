@extends('front.auth.layouts.app')

@section('title', '丸子平台-注册')

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
        <div class="login_box" id="captcha-container">
            <div class="login_title">
                立即注册
            </div>
            <form class="layui-form" action="">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-dianhua"></i></label>
                    <div class="layui-input-block">
                        <input type="text"  id="phone" name="phone" autocomplete="off" lay-verify="required|number" placeholder="请输入手机号" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item" id="captchaBox" style="height: 45px;display: none">
                </div>

                <div class="layui-form-item">
                    <div class="layui-row">
                        <div class="layui-col-xs7">
                            <label class="layui-form-label" style="top: 1px;"><i class="iconfont icon-youxiang"></i></label>
                            <div class="layui-input-block">
                                <input type="text" id="verification" name="verification_code" autocomplete="off" lay-verify="required|number" placeholder="手机验证码" class="layui-input" style="width: 115px">
                            </div>
                        </div>
                        <div class="layui-col-xs5">
                            <div style="margin-left: 10px;">
                                <button  class="layui-btn layui-btn-normal get-verification send-code-btn" style="width:100%; height:40px; line-height: 40px;">获取验证码</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-ad77"></i></label>
                    <div class="layui-input-block">
                        <input type="password" name="password" autocomplete="off" lay-verify="required" placeholder="请输入6-22位密码" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <i class="iconfont icon-ad77"></i>
                    </label>
                    <div class="layui-input-block">
                        <input type="password" name="password_confirmation" required="" lay-verify="required"  placeholder="请确认密码" autocomplete="off" class="layui-input layui-form-danger">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-zhanghao"></i></label>
                    <div class="layui-input-block">
                        <input type="text" name="name"  autocomplete="off" lay-verify="required" placeholder="请输入昵称" class="layui-input">
                    </div>
                </div>
                {{--<div class="layui-form-item">--}}
                    {{--<label class="layui-form-label"><i class="iconfont icon-youxiang"></i></label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<input type="text" name="email" autocomplete="off" lay-verify="required|email" placeholder="请输入邮箱" class="layui-input">--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="layui-form-item">
                    <button class="qs-btn" lay-submit="" lay-filter="register">注册</button>
                </div>
                <div class="layui-form-item login-link">
                    <a href="{{ route('login') }}" class="login" style="float: right;">立即登录</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});

        layui.use(['form', 'layedit', 'laydate', 'element'], function(){
            var form = layui.form
                ,layer = layui.layer;

            form.on('submit(register)', function (data) {
                $.post('{{ route('register') }}', {
                    name:data.field.name,
                    password:encrypt(data.field.password),
                    password_confirmation:encrypt(data.field.password_confirmation),
                    // email:data.field.email,
                    phone:data.field.phone,
                    geetest_challenge:data.field.geetest_challenge,
                    geetest_seccode:data.field.geetest_seccode,
                    geetest_validate:data.field.geetest_validate
                }, function (result) {
                    if (result.status == 1) {
                        location.reload();
                    } else {
                        layer.msg(result.message);
                    }
                    return false;
                }, 'json');
                return false;
            });
            $('body').height($(window).height());
        });
    </script>
    @include('front.auth.verification-js')
@endsection