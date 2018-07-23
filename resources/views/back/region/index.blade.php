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
                        <form id="search-flow" action="">
                            <div class="row">

                                <div class="form-group col-md-1">
                                    <select class="form-control" name="type">
                                        <option value="">所有类型</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-1">
                                    <select class="form-control" name="status">
                                        <option value="">所有状态</option>

                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <button class="btn btn-primary" type="button" id="export">导出</button>
                                </div>
                            </div>
                        </form>

                        <table class="layui-table" lay-size="sm">
                            <thead>
                            <tr>
                                <th>游戏</th>
                                <th>区名</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($regions as $item)
                                <tr>

                                    <td>{{ $item->name }}</td>
                                    <td>{{ optional($item->gameClass)->name }}</td>
                                    <td>{{ optional($item->gameType)->name }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>

                                            <button type="button" class="layui-btn layui-btn-normal layui-btn-mini complete" data-id="">完成</button>
                                            <button type="button" class="layui-btn layui-btn-mini layui-btn-danger refuse" data-id="">拒绝</button>

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
    <script>
        $('#export').click(function () {
            var url = "?export=1&" + $('#search-flow').serialize();
            window.location.href = url;
        });

        layui.use(['layer'], function () {


        });

    </script>
@endsection
