@extends('front.layouts.app')

@section('title', '财务 - 余额提现')

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
                <legend>提现</legend>
                <div class="layui-field-box">
                    提示：{{ \Setting::get('withdraw.tips') }}
                </div>
            </fieldset>

            <form class="layui-form" action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">可提现余额</label>
                    <div class="layui-input-inline">
                        <div class="" style="line-height: 30px">{{ auth()->user()->parent->userAsset->balance + 0 }}</div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">提现金额</label>
                    <div class="layui-input-inline">
                        <input type="text" name="amount" lay-verify="required|min_amount|integer" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">手续费</label>
                    <div class="layui-input-inline poundage" style="line-height: 30px;color:#cecece">
                        输入提现金额计算
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">实际到账金额</label>
                    <div class="layui-input-inline real-amount" style="line-height: 30px;color:#cecece">
                        输入提现金额计算
                    </div>
                </div>

                {{--<div class="layui-form-item">--}}
                    {{--<label class="layui-form-label">提现到银行卡</label>--}}
                    {{--<div class="layui-input-inline">--}}
                        {{--<div class="" style="line-height: 30px">--}}
                            {{--{{ optional(auth()->user()->parent->realNameCertification)->bank_card }}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="layui-form-item">
                    <label class="layui-form-label">支付宝账号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="alipay_account" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">支付宝姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="alipay_name" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">支付密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password" lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="qs-btn" lay-submit lay-filter="confirm-withdraw">确定</button>
                        <button type="reset" class="qs-btn">取消</button>
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

            var minAmount = '{{ \Setting::get('withdraw.min_amount') }}';

            form.verify({
                min_amount: function(value, item){
                    if (parseInt(value) < parseInt(minAmount)) {
                        return '最小提现金额为' + minAmount + '元';
                    }
                },
                integer: function (value, item) {
                    if (!/^[1-9]\d*$/.test(value)) {
                        return '提现金额必须为整数';
                    }
                }

            });

            // 确认提现
            form.on('submit(confirm-withdraw)', function (data) {

                $.post('{{ route('finance.balance-withdraw') }}', {
                    amount:data.field.amount,
                    alipay_account:data.field.alipay_account,
                    alipay_name:data.field.alipay_name,
                    password:encrypt(data.field.password)
                }, function (result) {
                    if (result.status == 1) {
                        layer.alert(result.message, {icon:6}, function () {
                            window.location.reload();
                        });
                    } else {
                        layer.msg(result.message, {icon:5});
                    }
                }, 'json');
                return false;
            });

            @if(optional(auth()->user()->parent->realNameCertification)->status != 2)
                layer.alert('您未进行实名认证，请先申请实名认证后再提现!', {
                    'title': '提示',
                    'btnAlign': 'c',
                    'closeBtn': 0
                }, function(index) {
                    window.location.href = '{{ route('real-name-certification.create') }}'
                });
            @endif

            $('input[name=amount]').blur(function () {
                var balance = '{{ auth()->user()->parent->userAsset->balance + 0 }}';
                var amount = parseFloat($(this).val());
                if (amount > parseFloat(balance)) {
                    layer.msg('提现金额不可大于可提现金额');
                }
                // 计算手续费
                var poundage = amount * 0.01;
                $('.poundage').css('color', '#000').html(poundage.toFixed(2));
                $('.real-amount').css('color', '#000').html((amount - poundage).toFixed(2));

                if (!amount) {
                    $('.poundage').css('color', '#cecece').html('输入提现金额计算');
                    $('.real-amount').css('color', '#cecece').html('输入提现金额计算');
                }
            });
        });
    </script>
@endsection
