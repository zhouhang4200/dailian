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
        <div class="title">注册邮箱地址</div>
        <form method="POST" action="{{ route('password.email') }}"  class="layui-form">
            {!! csrf_field() !!}
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="iconfont icon-youxiang"></i></label>
                <div class="layui-input-block">
                    <input type="email" name="email" required="" lay-verify="required" placeholder="请输入邮箱"
                           value="{{ old('email') }}" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo" style="width: 100%">发 送</button>
            </div>
        </form>
    </div>
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