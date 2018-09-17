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
                <h1 style="color:#ff7a00"> ¥ {{ request('amount') }}</h1>
                充值金额
            </div>

            <div class="" style="border: 1px solid #ddd;width: 300px;margin: 0 auto">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                ->merge(request('source') == 2 ? '/public/front/images/alipay_qr.png' : '/public/front/images/wechat_qr.png')
                ->size(300)->errorCorrection('H')
                ->generate(request('source') == 2 ? $result->qr_code : $result->code_url)) !!}">
            </div>

            <div style="margin:20px 0; text-align: center;font-size: 20px">打开{{ request('source') == 2 ? '支付宝' : '微信' }}“扫一扫”功能，扫码充值</div>
            <div style="margin:20px 0; text-align: center">
                <a href="{{ route('finance.balance-recharge') }}" class="qs-btn" style="color: #fff">返回</a>
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
                    layer.alert('充值成功');
                }
            });
        });
    </script>
@endsection


