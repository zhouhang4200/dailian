@extends('back.layouts.app')

@section('title', ' | 分类列表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">
                        分类列表
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <button class="btn btn-primary" type="button" id="create" style="float: right;margin-bottom: 10px">新增</button>
                        <div id="list"> @include('back.article.notice.category.list', compact('categories'))</div>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#time-start').datepicker();
        $('#time-end').datepicker();

        layui.use(['form', 'laytpl', 'element'], function(){
            var form = layui.form, layer = layui.layer;

            form.on('submit(delete)', function (data) {
                var id=this.getAttribute('lay-id');
                layer.confirm('您确认要删除吗', {icon: 3, title:'提示'}, function(index) {
                    $.post("{{ route('admin.article.category-notice-delete') }}", {id: id}, function (result) {
                        layer.msg(result.message, {time: 1000}, function () {
                            if (result.status == 1) {
                                $.get("{{  route('admin.article.category-notice') }}", function (data) {
                                    $('#list').html(data);
                                    form.render();
                                }, 'json');
                            }
                        });
                        return false;
                    });
                    layer.closeAll();
                });
                return false;
            });
        });

        $('#create').click(function () {
            window.location.href = "{{ route('admin.article.category-notice-create') }}";
        });

        $('#notice').click(function () {
            var category_id = this.getAttribute('category-id');
            window.location.href = "{{ route('admin.article.notice') }}?category_id="+category_id;
        });
    </script>
@endsection
