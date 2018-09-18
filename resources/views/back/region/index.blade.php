@extends('back.layouts.app')

@section('title', ' | 游戏区列表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">游戏区列表</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="name"  placeholder="游戏区名" value="{{ request('name') }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success" lay-submit="" lay-filter="search">搜索</button>
                                    <a href="{{ route('admin.region.create') }}" class="btn btn-success"  id="create" >新增</a>
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
                                <th>所属游戏</th>
                                <th>游戏区名</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($regions as $item)
                                <tr>

                                    <td>{{ optional($item->game)->name }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.region.update', ['id' => $item->id]) }}" class="btn btn-success" data-id="">修改</a>
                                        <button type="button" class="btn btn-danger" data-url="{{ route('admin.region.delete', ['id' => $item->id]) }}"  lay-submit lay-filter="delete">删除</button>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $regions->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

@endsection
