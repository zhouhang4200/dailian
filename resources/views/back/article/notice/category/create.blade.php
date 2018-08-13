@extends('back.layouts.app')

@section('title', ' | 添加分类')

@section('css')
    <style>
        .layui-table th, td{
            text-align: center;
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
                            <li class="layui-this" lay-id="add">添加分类</li>
                        </ul>
                        <div class="layui-tab-content">
                            <form class="layui-form" method="" action="">
                                {!! csrf_field() !!}
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*序号</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="sort" lay-verify="required|number" value="" autocomplete="off" placeholder="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*分类</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="name" value="" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <input type="hidden" name="parent_id" value="1">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*显示</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="是" checked="">
                                        <input type="radio" name="status" value="2" title="否">
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
        layui.use('form', function(){
            var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
            var layer = layui.layer;

            form.verify({
                number: [
                    /^[0-9]+$/
                    ,'填写格式不正确，必须为数字'
                ]
            });
            // 取消按钮
            $('.cancel').click(function () {
                window.location.href="{{ route('admin.article.category-notice') }}";
            });
            // 新增
            form.on('submit(store)', function (data) {
                $.post("{{ route('admin.article.category-notice-store') }}", {
                    data:data.field
                }, function (result) {
                    layer.msg(result.message, {time:1500}, function () {
                        if (result.status == 1) {
                            window.location.href="{{ route('admin.article.category-notice') }}";
                        }
                    });
                });
                return false;
            });
        });
    </script>
@endsection