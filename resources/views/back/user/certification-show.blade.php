@extends('back.layouts.app')

@section('title', ' | 用户资料')

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
        }

        .none {
            display: none;
        }
        .layui-anim {
            color: #1E9FFF !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class=""><span>首页</span></li>
                <li class=""><a href="{{ route('admin.user') }}"><span>实名认证详情</span></a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <div class="main-box-body clearfix">
                    <div class="table-responsive">
                        <div class="layui-tab layui-tab-brief" lay-filter="detail">
                            <div class="layui-tab-content">
                                <div class="layui-tab-item detail"></div>
                                <div class="layui-tab-item authentication layui-show ">
                                    @if($realNameCertification)
                                        <input id="userId" type="hidden" name="userId" value="{{ $realNameCertification->user_id}}">
                                            <div class="row">
                                                <div class="col-xs-12" style="margin-bottom: 20px;">
                                                    <div id="gallery-photos-lightbox">
                                                        <ul class="clearfix gallery-photos">
                                                            <li class="col-md-4">
                                                                <a href="{{ $realNameCertification->identity_card_front or '' }}" class="photo-box image-link"
                                                                   style="background-image: url('{{ $realNameCertification->identity_card_front or '' }}');"></a>
                                                                <span class="thumb-meta-time"><i class="fa fa-clock-o"></i> 身份证正面照</span>
                                                            </li>
                                                            <li class="col-md-4">
                                                                <a href="{{ $realNameCertification->identity_card_back or '' }}" class="photo-box image-link"
                                                                   style="background-image: url('{{ $realNameCertification->identity_card_back or '' }}');"></a>
                                                                <span class="thumb-meta-time"><i class="fa fa-clock-o"></i> 身份证背面照</span>
                                                            </li>
                                                            <li class="col-md-4">
                                                                <a href="{{ $realNameCertification->identity_card_hand or '' }}" class="photo-box image-link"
                                                                   style="background-image: url('{{ $realNameCertification->identity_card_hand or '' }}');"></a>
                                                                <span class="thumb-meta-time"><i class="fa fa-clock-o"></i> 手持身份证的本人正面照</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class='self'>
                                                        <form class="layui-form">
                                                            <div  class="ident">
                                                                <div class='personal'>
                                                                    <div class="layui-form-item">
                                                                        <label class="layui-form-label">真实姓名</label>
                                                                        <div class="layui-input-block">
                                                                            <input type="text" name="real_name" lay-verify="required"
                                                                                   value="{{ $realNameCertification->real_name }}"
                                                                                   autocomplete="off" placeholder=""
                                                                                   class="layui-input">
                                                                        </div>
                                                                    </div>
                                                                    <div class="layui-form-item">
                                                                        <label class="layui-form-label">开户银行卡号</label>
                                                                        <div class="layui-input-block">
                                                                            <input type="text" disabled="disabled" name="bank_card" lay-verify="required"
                                                                                   value="{{ $realNameCertification->bank_card }}"
                                                                                   autocomplete="off" placeholder=""
                                                                                   class="layui-input">
                                                                        </div>
                                                                    </div>
                                                                    <div class="layui-form-item">
                                                                        <label class="layui-form-label">开户银行名称</label>
                                                                        <div class="layui-input-block">
                                                                            <input type="text" disabled="disabled" name="bank_name" lay-verify="required"
                                                                                   value="{{ $realNameCertification->bank_name }}"
                                                                                   autocomplete="off" placeholder=""
                                                                                   class="layui-input">
                                                                        </div>
                                                                    </div>
                                                                    <div class="layui-form-item">
                                                                        <label class="layui-form-label">身份证号码</label>
                                                                        <div class="layui-input-block">
                                                                            <input type="text" disabled="disabled" name="identity_card"
                                                                                   lay-verify="required|identity"
                                                                                   value="{{ $realNameCertification->identity_card }}"
                                                                                   placeholder="" autocomplete="off"
                                                                                   class="layui-input">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="layui-form-item">
                                                                <div class="layui-input-block">
                                                                    <button type="button" class="layui-btn layui-btn-normal" lay-submit="" id="pass"
                                                                            lay-filter="">通过
                                                                    </button>
                                                                    <button type="button" class="layui-btn layui-btn-normal" lay-submit="" id="refuse"
                                                                            lay-filter="">拒绝
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                    @else
                                        <h2>用户没有提交认证资料</h2>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/back/js/jquery.magnific-popup.min.js"></script>
    <script>
        $(function() {
            $('#gallery-photos-lightbox').magnificPopup({
                type: 'image',
                delegate: 'a',
                gallery: {
                    enabled: true
                }
            });
        });

        layui.use('form', function () {
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
            var layer = layui.layer;
            var userId = $('#userId').val();

            // 同意
            $('#pass').click(function () {
                layer.confirm('通过认证？' , function (layerConfirm) {
                    layer.close(layerConfirm);
                    $.post("{{ route('admin.user.certification-pass') }}", {userId:userId},function (data) {
                        if (data.status === 1) {
                            layer.msg(data.message,  {icon:6, time:1500}, function () {
                                window.location.href="{{ route('admin.user.certification') }}";
                            });
                        } else {
                            layer.msg(data.message, {icon:5, time:1500});
                        }
                    }, 'json');
                });
            });

            // 拒绝
            $('#refuse').click(function () {
                layer.confirm('拒绝认证？' , function (layerConfirm) {
                    layer.close(layerConfirm);
                    layer.prompt({title: '请输入原因',formType: 2},function(value, promptIndex, elem){
                        $.post("{{ route('admin.user.certification-refuse') }}", {remark:value, userId:userId},function (data) {
                            if (data.status === 1) {
                                layer.msg(data.message, {icon:6, time:1500}, function () {
                                    window.location.href="{{ route('admin.user.certification') }}";
                                });
                            } else {
                                layer.msg(data.message, {icon:5, time:1500});
                            }
                            layer.close(promptIndex);
                        }, 'json');
                    });
                });
            });
        });
    </script>
@endsection


