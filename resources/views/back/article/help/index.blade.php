@extends('back.layouts.app')


@section('title', ' | 帮助中心')


@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">
                            帮助中心
                    </li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form">
                            <input type="hidden" name="category_id" value="{{ $categoryId }}">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="title"  placeholder="标题" value="{{ $title }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="search">搜索</button>
                                    <button class="layui-btn layui-btn-normal" type="button" id="create" category-id="{{ $categoryId }}">新增</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div id="list"> @include('back.article.help.list', compact('articles'))</div>
                        {{ $articles->appends([
                            'title' => $title,
                            'category_id' => $categoryId
                        ])->links() }}
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
                var categoryId = this.getAttribute('category-id');
                layer.confirm('您确认要删除吗', {icon: 3, title: '提示'}, function (index) {
                    $.post("{{ route('admin.article.help-delete') }}", {id:id}, function (result) {
                        layer.msg(result.message, {time: 1500}, function () {
                            if (result.status == 1) {
                                $.get("{{  route('admin.article.help') }}", {category_id:categoryId}, function (data) {
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
            var category_id = this.getAttribute('category-id');
            window.location.href = "{{ route('admin.article.help-create') }}?category_id="+category_id;
        });
    </script>
@endsection
