@extends('front.auth.layouts.app')

@section('title', '密码找回')

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
    <form method="POST" action="{{ route('password.email') }}"  class="layui-form">
        {!! csrf_field() !!}
            <div class="container" style="background: rgb(236,239,241)">
                <div class="layui-form-item">
                    <input type="email" name="email" required="" lay-verify="required" placeholder="请输入邮箱" value="{{ old('email') }}" autocomplete="off" class="layui-input layui-form-danger">
                    <i class="layui-icon icon">&#xe64c;</i>
                </div>

                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo" style="width: 100%">发 送</button>
                </div>
            </div>
    </form>
@endsection

@section('js')
    <script>
        layui.use(['form', 'layedit', 'laydate'], function(){
            var form = layui.form
                ,layer = layui.layer;

            var error = "{{ $errors->count() > 0 ? '请填写注册时的邮箱!' : '' }}";
            var succ = "{{ session('status') ? '发送成功!' : '' }}";

            if (error) {
                layer.msg(error, {icon: 5, time:2000});            } else if (succ) {
                layer.msg(succ, {icon: 6, time:2000});            }
        });
    </script>
@endsection