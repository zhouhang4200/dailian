@extends('front.auth.layouts.app')

@section('css')
    <link rel="stylesheet" href="/front/lib/css/email.css">
@section('header')
    <div class="header">
        <img src="/front/images/title1.png" alt="">
    </div>
@endsection

@section('main')
    <div class="container">
        <div class="title">重置密码</div>
        <form method="POST" action="{{ route('password.request') }}"  class="layui-form">
            {!! csrf_field() !!}
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="iconfont icon-youxiang"></i></label>
                <div class="layui-input-block">
                    <input type="email" name="email" required="" lay-verify="required" placeholder="请输入邮箱" value="{{ old('email') }}"
                       autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layui-form-label"><i class="iconfont icon-youxiang"></i></label>
                        <input type="text" name="vercode"  lay-verify="required" placeholder="图形验证码" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo" style="width: 100%">获取验证码</button>
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
                    <input type="password" name="password_confirmation" required="" lay-verify="required" placeholder="确认新密码"
                       autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo" style="width: 100%">确认</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        layui.use(['form', 'layedit', 'laydate'], function(){
            var form = layui.form
                ,layer = layui.layer;

            var errorPassword = "{{ $errors->count() > 0 && array_key_exists('password', $errors->toArray()) && $errors->toArray()['password'] ? '请按要求填写密码!' : '' }}";
            var errorEmail = "{{ $errors->count() > 0 && array_key_exists('email', $errors->toArray()) && $errors->toArray()['email'] ? '邮箱填写错误!' : '' }}";

            if(errorEmail) {
                layer.msg(errorEmail, {icon: 5, time:1500});            } else if (errorPassword) {
                layer.msg(errorPassword, {icon: 5, time:1500});            }
        });
    </script>
@endsection