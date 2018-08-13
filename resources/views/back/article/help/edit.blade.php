@extends('back.layouts.app')


@section('title', ' | 修改帮助')

@section('css')
    <style>
        .layui-table th, td{
            text-align: center;
        }
    </style>
    <script src="/ueditor/ueditor.config.js"></script>
    <script src="/ueditor/ueditor.all.js"></script>
    <script src="/ueditor/lang/zh-cn/zh-cn.js"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box">
                <div class="main-box-body clearfix">
                    <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                        <ul class="layui-tab-title">
                            修改帮助
                        </ul>
                        <div class="layui-tab-content">
                            <form class="layui-form" method="" action="">
                                {!! csrf_field() !!}
                                <input type="hidden" name="article_category_id" value="{{ $categoryId }}">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*序号</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="sort" lay-verify="required|number" value="{{ $article->sort }}" autocomplete="off" placeholder="" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*标题</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="title" value="{{ $article->title }}" lay-verify="required" placeholder="" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*内容</label>
                                    <div class="layui-input-block">
                                        <textarea id="content" name="content" lay-verify="content">{{ $article->content }}</textarea>
                                        <script type="text/javascript">
                                            UE.getEditor('content',{initialFrameWidth:1181,initialFrameHeight:300})
                                        </script>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">*显示</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="status" value="1" title="是" {{ $article->status == 1 ? 'checked' : '' }}>
                                        <input type="radio" name="status" value="2" title="否" {{ $article->status == 2 ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="btn btn-success" lay-submit="" lay-id="{{ $article->id }}" lay-filter="update">确认</button>
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

            var categoryId = $("input[name=article_category_id]").val();
            // 取消按钮
            $('.cancel').click(function () {
                window.location.href="{{ route('admin.article.help') }}?category_id="+categoryId;
            });
            // 新增
            form.on('submit(update)', function (data) {
                var id=this.getAttribute('lay-id');
                $.post("{{ route('admin.article.help-update') }}", {
                    id:id,
                    data:data.field
                }, function (result) {
                    layer.msg(result.message, {time:1000}, function () {
                        if (result.status == 1) {
                            window.location.href="{{ route('admin.article.help') }}?category_id="+categoryId;
                        }
                    });
                });
                return false;
            });
        });
    </script>
@endsection