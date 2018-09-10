@extends('back.layouts.app')

@section('title', ' | 添加封号')

@section('css')
    <style>
        .layui-form-item .layui-form-label {
            width: 125px;
        }
        .layui-form-item .layui-input-block {
            margin-left: 125px;
        }
        .layui-form-item {
            width: 600px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">
                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" lay-id="add">添加封号</li>
                        </ul>
                        <div class="layui-tab-content">
                            <form class="layui-form" method="" action="">
                                {!! csrf_field() !!}
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*被封号方ID</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="user_id" lay-verify="required|number" value="" autocomplete="off" placeholder="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*封号开始时间</label>
                                    <div class="layui-input-block">
                                        <input type="text" class="form-control" lay-verify="required" id="time-start" name="start_time" value="">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">封号结束时间</label>
                                    <div class="layui-input-inline" style="width: 380px">
                                        <input type="text" class="form-control" id="time-end" placeholder="" name="end_time" value="">
                                    </div>
                                    <input style="position:relative;float: left" type="checkbox" name="type" value="2">永久
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*封号原因</label>
                                    <div class="layui-input-block">
                                        <textarea id="content" name="reason" lay-verify="content" placeholder="" class="layui-textarea"></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">备注</label>
                                    <div class="layui-input-block">
                                        <textarea id="content" name="remark" lay-verify="content" placeholder="" class="layui-textarea"></textarea>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="btn btn-success" lay-submit="" lay-filter="store">确认</button>
                                        <button type="button" class="layui-btn layui-btn-normal cancel" >取消</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        layui.use(['form', 'laydate'], function(){
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
            var layer = layui.layer;
            var laydate = layui.laydate;

            //日期时间范围选择
            laydate.render({
                elem: '#time-start'
                ,type: 'datetime'
            });

            //日期时间范围选择
            laydate.render({
                elem: '#time-end'
                ,type: 'datetime'
            });

            // 取消按钮
            $('.cancel').click(function () {
                window.location.href="{{ route('admin.blockade-account') }}";
            });

            // 新增
            form.on('submit(store)', function (data) {
                $.post("{{ route('admin.blockade-account.store') }}", {
                    data:data.field
                }, function (result) {
                    layer.msg(result.message, {time:1000}, function () {
                        if (result.status == 1) {
                            window.location.href="{{ route('admin.blockade-account') }}";
                        }
                    });
                });
                return false;
            });
        });
    </script>
@endsection