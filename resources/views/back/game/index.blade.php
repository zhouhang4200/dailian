@extends('back.layouts.app')

@section('title', ' | 游戏列表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">游戏列表</li>
                </ul>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="name"  placeholder="游戏名" value="{{ request('name') }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="layui-btn layui-btn-normal" lay-submit="" lay-filter="search">搜索</button>
                                    <a href="{{ route('admin.game.create') }}" class="layui-btn layui-btn-normal" type="button" id="create" >新增</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <table class="layui-table" lay-size="sm">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>游戏名</th>
                                <th>类型</th>
                                <th>类别</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($games as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ optional($item->gameClass)->name }}</td>
                                    <td>{{ optional($item->gameType)->name }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.game.update', ['id' => $item->id]) }}" class="layui-btn layui-btn-normal layui-btn-mini complete" data-id="">修改</a>
                                        <button type="button" class="layui-btn layui-btn-mini layui-btn-danger" data-url="{{ route('admin.game.delete', ['id' => $item->id]) }}"  lay-submit lay-filter="delete">删除</button>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $games->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        layui.use(['form', 'layedit', 'laydate'], function(){
            var form = layui.form,layer = layui.layer;

            form.on('submit(delete)', function(data){
                layer.confirm('确定要删除吗?', {icon: 3, title:'提示'}, function(index){
                    $.post($(data.elem).attr('data-url'), function (result) {
                        if (result.status == 1) {
                            layer.msg(result.message, {time:600}, function () {
                                location.reload()
                            });
                        } else {
                            layer.msg(result.message)
                        }
                    }, 'json');
                    layer.close(index);
                    return true;
                });
            });
        });
    </script>
@endsection
