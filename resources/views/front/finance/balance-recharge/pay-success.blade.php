@extends('front.layouts.app')

@section('title', '财务 - 余额充值')

@section('css')
    <style>
        .layui-form-item .layui-inline {
            margin-bottom: 5px;
            margin-right: 5px;
        }
        .layui-form-mid {
            margin-right: 4px;
        }
        .layui-form-radio {
            margin: 2px 10px 0 0;
        }
    </style>
@endsection

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-body">
            <fieldset class="layui-elem-field">
                <legend>充值</legend>
                <div class="layui-field-box">
                    提示：最低充值1元，支付成功后，充值金额需等待1-5分钟到账
                </div>
            </fieldset>
            <div class="" style="width: 300px;margin: 20px auto;text-align: center">
                <div class="" style="width: 300px;height:300px;margin: 0 auto">
                    <i class="iconfont icon-qunfengchongzhichenggong" style="font-size: 260px;color: #198cff;line-height:300px"></i>
                </div>
                <div style="margin:20px 0; text-align: center">
                    <a href="{{ route('profile') }}" class="qs-btn" style="color: #fff">返回</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form', 'element'], function(){
            var form = layui.form, layer = layui.layer, element = layui.element;

            var socket = io("{{ env('SOCKET') }}");
            var user_id ="{{ auth()->user()->parent_id }}";
            socket.on("notification:recharge", function (data) {
                if (data.user_id == user_id) {

                }
            });

        });
    </script>
@endsection


