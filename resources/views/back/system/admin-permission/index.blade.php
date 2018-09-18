@extends('back.layouts.app')

@section('title', ' | 权限管理')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">权限管理</li>
                </ul>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form">
                            <input type="hidden" name="category_id" value="">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="name"  placeholder="权限名" value="{{ request('name') }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success" lay-submit="" lay-filter="search">搜索</button>
                                    <a href="{{ route('admin.admin-permission.create') }}" class="btn btn-success"  id="create" >新增</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="layui-tab-content">
                    <table id="data-table" class="table table-hover dataTable no-footer" role="grid"
                           aria-describedby="data-table_info">
                        <thead>
                        <tr role="row">
                            <th>ID</th>
                            <th>权限名</th>
                            <th>权限组</th>
                            <th>路由名</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $item)
                            <tr role="row" class="odd even">
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ optional($item->group)->name }}</td>
                                <td>{{ $item->route_name }}</td>
                                <td>
                                    <a href="{{ route('admin.admin-permission.update', ['id' => $item->id]) }}" class="btn btn-success">
                                        修改
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $permissions->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $('#export').click(function () {
            var url = "?export=1&" + $('#search-flow').serialize();
            window.location.href = url;
        });

        layui.use(['layer'], function () {


        });

    </script>
@endsection