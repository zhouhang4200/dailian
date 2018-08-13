@extends('back.layouts.app')

@section('title', ' | 管理员列表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">管理员列表</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form class="layui-form">
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="text" class="layui-input" name="name"  placeholder="用户名" value="{{ request('name') }}">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success" lay-submit="" lay-filter="search">搜索</button>
                                    <a href="{{ route('admin.admin-user.create') }}" class="btn btn-success" type="button" id="create" >新增</a>
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
                            <th>账号</th>
                            <th>邮箱</th>
                            <th>角色</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($admins as $item)
                            <tr role="row" class="odd even">
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ implode(',', $item->cachedRoles()->pluck('name', 'id')->toArray()) }}</td>
                                <td>
                                    <a href="{{ route('admin.admin-user.update', ['id' => $item->id]) }}" class="layui-btn layui-btn-normal layui-btn-mini">
                                       修改
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $admins->appends(request()->all())->links() }}
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