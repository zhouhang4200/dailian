@extends('back.layouts.app')

@section('title', ' | 仲裁详情')

@section('css')
    <link rel="stylesheet" href="/back/css/bootstrap-fileinput.css">
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class=""><span>首页</span></li>
                <li class=""><a href="{{ route('admin.game-leveling-order') }}"><span>仲裁订单</span></a></li>
                <li class="active"><span>仲裁详情</span></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <div class="main-box-body clearfix">
                    <div class="table-responsive" id="show-content">
                        @include('back.order.game-leveling-order-complain.show-content');
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="md-overlay"></div>
@endsection

@section('pop')
    <div class="arbitration-pop" style="display: none; padding: 20px 20px 0 20px">
        <div class="">
            <form class="form-horizontal layui-form" method="POST" action="">
                {!! csrf_field() !!}
                <input type="hidden" name="trade_no">

                <div class="form-group">
                    <label class="col-lg-4 control-label">*需发单方支付代练费（元）</label>
                    <div class="col-lg-8">
                        <input type="text" name="amount" lay-verify="required|number"  autocomplete="off"
                               placeholder="请输入代练费" class="layui-input" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">订单代练费（元）</label>
                    <div class="col-lg-8">
                        <input type="text" name="order_amount" id="order_amount" lay-verify=""
                               autocomplete="off" placeholder="" class="layui-input order_amount"  disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">*需接单方赔付保证金（元）</label>
                    <div class="col-lg-8">
                        <input type="text" name="deposit" lay-verify="required|number"
                               autocomplete="off"
                               placeholder="请输入保证金" class="layui-input" >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">订单安全保证金（元）</label>
                    <div class="col-lg-8">
                        <input type="text" name="order_security_deposit"  lay-verify=""  autocomplete="off"
                               placeholder="" class="layui-input safe"  disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">订单效率保证金（元）</label>
                    <div class="col-lg-8">
                        <input type="text" name="order_efficiency_deposit"  lay-verify=""  autocomplete="off"
                               placeholder="" class="layui-input effect"  disabled>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-12">
                        <textarea name="" class="layui-textarea" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label"></label>
                    <div class="col-lg-8">
                        <button class="btn btn-success" lay-submit lay-filter="confirm-apply-consult">确定</button>
                        <button class="btn btn-success" lay-submit lay-filter="confirm-apply-consult">取消</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>

        layui.use(['form', 'layedit', 'laydate', 'laytpl', 'element', 'carousel'], function(){
            var form = layui.form, layer = layui.layer, layTpl = layui.laytpl, element = layui.element, carousel =  layui.carousel;
            layer.photos({
                anim: -1,
                photos: '#layer-photos-demo'
            });

            // 发送仲裁留言
            form.on('submit(send-complain-message)', function (data) {
                var image = $('.pic-add img').attr('src');
                if (data.field.content) {
                    $.post("{{ route('admin.game-leveling-order-complain.send-message') }}", {
                        'trade_no': "{{ $order->trade_no }}",
                        'content': data.field.content,
                        'image': image
                    }, function (data) {
                        if (data.status === 1) {
                            layer.msg(data.message, {icon: 1});
                            complainInfo();
                        } else {
                            layer.msg(data.message, {icon: 5});
                            return false;
                        }
                    }, 'json');
                } else {
                    layer.msg('请输入要发送的内容', {icon: 5});
                }
                return false;
            });

            // 订单仲裁信息
            function complainInfo() {
                $.get('{{ route('admin.game-leveling-order-complain.show', ['trade_no' => $order->trade_no])  }}', function (result) {
                    $('#show-content').html(result.content);
                }, 'json');
            }

            // 仲裁弹窗
            form.on('submit(arbitration-pop)', function (data) {
                // $('input[name=trade_no]').val($(data.elem).attr('data-no'));
                layer.open({
                    type: 1,
                    shade: 0.2,
                    title: '仲裁',
                    area: ['550px'],
                    content: $('.arbitration-pop')
                });
                return false;
            });

            $('#show-content').on('click', '.photo', function () {
                var imgSrc = $(this).attr('data-img');
                layer.photos({
                    photos: {
                        "id": 123, //相册id
                        "data": [   //相册包含的图片，数组格式
                            {
                                "src": imgSrc, //原图地址
                                "thumb": imgSrc //缩略图地址
                            }
                        ]
                    },
                    anim: -1,
                    shade: 0.8
                });
            });
        });
    </script>
@endsection
