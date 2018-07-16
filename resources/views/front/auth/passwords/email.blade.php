@extends('front.auth.layouts.app')

@section('title', '密码找回')

@section('css')
    <style>
        .input-container input {
            height:40px;
        }
    </style>
@endsection

@section('main')
    <form method="POST" action="{{ route('password.email') }}"  class="layui-form">
        {!! csrf_field() !!}
            <div class="container">
                <div class="input-container">
                    <div class="title">邮件地址</div>
                    <div class="layui-form-item">
                        <input type="email" name="email" required="" lay-verify="required" placeholder="请输入" value="{{ old('email') }}" autocomplete="off" class="layui-input layui-form-danger">
                        <i class="layui-icon icon">&#xe612;</i>
                    </div>

                    <div class="layui-form-item">
                        <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo" style="width: 100%">发 送</button>
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

            var error = "{{ $errors->count() > 0 ? '请填写注册时的邮箱!' : '' }}";
            var succ = "{{ session('status') ? '发送成功!' : '' }}";

            if (error) {
                layer.msg(error, {icon: 5, time:2000});            } else if (succ) {
                layer.msg(succ, {icon: 6, time:2000});            }
        });
    </script>
@endsection