@extends('front.auth.layouts.app')

@section('title', '重置密码')

@section('css')
    <link rel="stylesheet" href="/front/css/layui-rewrit.css">
    <link rel="stylesheet" href="/front/css/login.css">
    <style>
        .input-container input {
            height:40px;
        }
    </style>
@endsection

@section('header')
    <div class="header">
        <div class="container">
            <img src="/front/images/title.png" alt="">
        </div>
    </div>
@endsection

@section('main')
    <form method="POST" action="{{ route('password.request') }}"  class="layui-form">
        {!! csrf_field() !!}
            <div class="container">
                <div class="input-container">
                    <div class="title">重置密码</div>
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="layui-form-item">
                        <input type="email" name="email" required="" lay-verify="required" placeholder="请输入邮箱" value="{{ old('email') }}" autocomplete="off" class="layui-input layui-form-danger">
                        <i class="layui-icon icon">&#xe64c;</i>
                    </div>
                    <div class="layui-form-item ">
                        <input type="password" name="password" required="" lay-verify="required" placeholder="6-22位密码" autocomplete="off" class="layui-input layui-form-danger">
                        <i class="layui-icon icon"> &#x1005;</i>
                    </div>
                    <div class="layui-form-item ">
                        <input type="password" name="password_confirmation" required="" lay-verify="required" placeholder="确认密码" autocomplete="off" class="layui-input layui-form-danger">
                        <i class="layui-icon icon"> &#x1005;</i>
                    </div>
                    <div class="layui-form-item">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo" style="width: 100%">确认</button>
                    </div>
                </div>
            </div>
    </form>
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