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
            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">充值账号呢称</label>
                    <div class="layui-input-block">
                        <div class="" style="line-height: 30px">{{ auth()->user()->name }}</div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">充值账号ID</label>
                    <div class="layui-input-block">
                        <div class="" style="line-height: 30px">{{ auth()->user()->id }}</div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">充值金额</label>
                    <div class="layui-input-block">
                        <input type="radio" name="amount" value="10" title="10元" checked>
                        <input type="radio" name="amount" value="50" title="50元">
                        <input type="radio" name="amount" value="100" title="100元">
                        <input type="radio" name="amount" value="0" title="自定义金额"> <input type="text" name="custom_amount" style="height: 20px">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">充值方式</label>
                    <div class="layui-input-block">
                        <input type="radio" name="source" value="1" title="支付宝 <i class='iconfont  icon-zhifubao' style='color:#198cff;font-size:23px'></i>" checked>
                        <input type="radio" name="source" value="2" title="微信 <i class='iconfont  icon-weixin' style='color:#5FB878;font-size:23px'></i>">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="qs-btn" lay-submit lay-filter="pay">下一步</button>
                        <button type="reset" class="qs-btn">返回</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
@section('js')
    <script>
        layui.use(['form', 'element'], function(){
            var form = layui.form, layer = layui.layer, element = layui.element;
            // 支付
            form.on('submit(pay)', function (data) {
                var amount = data.field.amount;
                if (amount == 0 && data.field.custom_amount == '') {
                    layer.msg('请输入要充值的金额');
                    return false;
                } else if (amount == 0 && data.field.custom_amount != '') {
                    amount = data.field.custom_amount;
                }
                window.location.href = '{{ route('finance.balance-recharge.pay') }}/?amount=' + amount + '&source=' + data.field.source;
                return false;
            });
        });
    </script>
@endsection
