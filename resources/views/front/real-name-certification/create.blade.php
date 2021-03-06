@extends('front.layouts.app')

@section('title', '账号 - 实名认证')

@section('css')
    <style>
        .ident > .company > .layui-form-item > .layui-form-label {
            width: 110px;
        }

        .ident > .company > .layui-form-item > .layui-input-block {
            margin-left: 170px;
        }

        .ident > .company > .layui-form-item > .layui-upload > .layui-btn {
            margin-left: 30px;
        }

        .ident > .company > .layui-form-item > .layui-upload > .layui-upload-list {
            width: 500px;
            height: 300px;
            margin-left: 170px;
        }

        .ident > .company > .layui-form-item > .layui-upload > .layui-upload-list > img {
            width: 100%;
            height: 100%;
        }

        /*分割线*/
        .ident > .personal > .layui-form-item > .layui-form-label {
            width: 110px;
        }

        .ident > .personal > .layui-form-item > .layui-input-block {
            margin-left: 170px;
        }

        .ident > .personal > .layui-form-item > .layui-upload > .layui-btn {
            margin-left: 30px;
        }

        .ident > .personal > .layui-form-item > .layui-upload > .layui-upload-list {
            width: 500px;
            height: 300px;
            margin-left: 170px;
            background-size: cover !important;
            background-position: center !important;
        }

        .ident > .personal > .layui-form-item > .layui-upload > .layui-upload-list > img {
            width: 100%;
            height: 100%;
            visibility:
        }

        .ident > .layui-form-item > .layui-input-block {
            margin-left: 170px;
        }

        .layui-form-item .layui-input-block input{
            width: 500px;
        }
        .none {
            display: none;
        }

        .type > .layui-form-item > .layui-input-block {
            margin-left: 170px;
        }

        .layui-anim {
            color: #1E9FFF !important;
        }
    </style>
@endsection

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-body">
            <form class="layui-form" action="" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div style="width: 80%" class="ident">
                    <input type="hidden" name="type" value='1'>
                    <div class='personal'>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*真实姓名</label>
                            <div class="layui-input-block">
                                <input type="text" name="real_name" lay-verify="required" value=""
                                       autocomplete="off" placeholder="请输入您的真实姓名" class="layui-input">
                            </div>
                        </div>
                        {{--<div class="layui-form-item">--}}
                            {{--<label class="layui-form-label">*开户银行卡号</label>--}}
                            {{--<div class="layui-input-block">--}}
                                {{--<input type="text" name="bank_card"  lay-verify="required|bankcode" value=""--}}
                                       {{--autocomplete="off" placeholder="请输入开户银行卡号" class="layui-input">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="layui-form-item">--}}
                            {{--<label class="layui-form-label">*开户银行名称</label>--}}
                            {{--<div class="layui-input-block">--}}
                                {{--<input type="text" name="bank_name" lay-verify="required" value=""--}}
                                       {{--autocomplete="off" placeholder="请输入详细银行名称如XX行XX支行" class="layui-input">--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="layui-form-item">
                            <label class="layui-form-label">*支付宝账号</label>
                            <div class="layui-input-block">
                                <input type="text" name="alipay_account"  lay-verify="required" value=""
                                       autocomplete="off" placeholder="请输入支付宝账号" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">*身份证号码</label>
                            <div class="layui-input-block">
                                <input type="text" name="identity_card" lay-verify="required|identity"
                                       value="" placeholder="请输入身份证号码" autocomplete="off"
                                       class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">*身份证正面照</label>
                            <div class="layui-upload">
                                <button type="button" class="qs-btn layui-btn-normal layui-btn-small upload-images" style="margin-left: 40px;">
                                    上传图片
                                </button>
                                <input class="layui-upload-file" type="file" name="identity_card_front">
                                <div class="layui-upload-list">
                                    <img class="layui-upload-img" id="demo2">
                                    <input type="hidden" lay-verify="" name="identity_card_front" value="">
                                    <p id="demoText"></p>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*身份证背面照</label>
                            <div class="layui-upload">
                                <button type="button" class="qs-btn layui-btn-normal layui-btn-small upload-images" style="margin-left: 40px;">
                                    上传图片
                                </button>
                                <input class="layui-upload-file" lay-verify="" type="file" name="identity_card_back">
                                <div class="layui-upload-list">
                                    <img class="layui-upload-img" id="demo3">
                                    <input type="hidden" name="identity_card_back" value="">
                                    <p id="demoText"></p>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">*手持身份证的本人正面照</label>
                            <div class="layui-upload">
                                <button type="button" class="qs-btn layui-btn-normal layui-btn-small upload-images" style="margin-left: 40px;">
                                    上传图片
                                </button>
                                <input class="layui-upload-file" lay-verify="" name="identity_card_hand" value="">
                                <div class="layui-upload-list">
                                    <img class="layui-upload-img" id="demo4">
                                    <input type="hidden" name="identity_card_hand" value="">
                                    <p id="demoText"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="qs-btn layui-btn-normal" lay-submit="" lay-filter="store">确定</button>
                            <button type="button" class="qs-btn layui-btn-normal" lay-filter='' id="cancel">取消</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
<!--START 底部-->
@section('js')
    <script>
        layui.use(['form', 'upload'], function () {
            var $ = layui.jquery, upload = layui.upload, layer = layui.layer;var form = layui.form;

            form.verify({
                bankcode:[
                    /^([1-9]{1})(\d{12}|\d{15}|\d{18})$/,
                    '请输入正确的银行卡号'
                ]
            });

            upload.render({
                elem: '.upload-images',
                url: "{{ route('real-name-certification.image-update') }}",
                size: 3000,
                accept: 'file',
                exts: 'jpg|jpeg|png|gif',
                before: function (obj) {
                    var dom = this;
                    obj.preview(function (index, file, result) {
                        dom.item.nextAll('div').css('background', 'url(' + result + ')');
                    });
                },
                done: function (res) {
                    var dom = this;
                    //如果上传失败
                    if (res.status == 2) {
                        return layer.msg('上传失败');
                    }
                    dom.item.nextAll('div').find('input').val(res.path);
                }
            });

            form.on('submit(store)', function (data) {
                var identity_card_front = $("input[name=identity_card_front]").val();
                var identity_card_back = $("input[name=identity_card_back]").val();
                var identity_card_hand = $("input[name=identity_card_hand]").val();

                if (identity_card_front.length == 0 || identity_card_back.length == 0 || identity_card_hand.length == 0) {
                    layer.msg('请上传相关图片', {
                        time:1500,
                        icon:5
                    });
                    return false;
                }

                $.post("{{ route('real-name-certification.store') }}", {data:data.field}, function (result) {
                    if (result.status == 1) {
                        layer.msg(result.message, {time:1500, icon:6}, function () {
                            window.location.href="{{ route('real-name-certification') }}";
                        });
                    } else {
                        layer.msg(result.message, {time:1500, icon:5})
                    }
                    return false;
                });
                return false;
            });

            $("#cancel").click(function () {
                window.location.href="{{ route('real-name-certification') }}";
            });
        });
    </script>
@endsection