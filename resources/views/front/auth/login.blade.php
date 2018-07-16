@extends('front.auth.layouts.app')

@section('title', '丸子平台-登录')

@section('css')
@endsection

@section('main')
    <div class="container">
        <div class="login_img">
            <img src="/front/images/login_img.png" alt="">
        </div>
        <div class="login_box" id='login'>
            <div class="login_title">
                账号登录
            </div>
            <form action="" class="layui-form">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label"><i class="iconfont icon-zhanghao-copy"></i></label>
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
                <div class="layui-form-item">
                    {!! Geetest::render() !!}
                </div>
                <div class="layui-form-item">
                    <button class="qs-btn" lay-submit="" lay-filter="login">登录</button>
                </div>
                <div class="layui-form-item login-link">
                    <a href="{{ route('password.request') }}" class = "register" style="float: right;">忘记密码</a>
                    <a href="{{ route('register') }}" style="float: left;">立即注册</a>
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
                } else {
                layer.msg(result.message);
                }
            return false;
            });
            return false;
        });

        $('body').height($(window).height());
        });
    </script>
@endsection