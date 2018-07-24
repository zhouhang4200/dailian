@extends('back.layouts.app')

@section('title', ' | 用户资金明细')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">用户资金明细</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form id="search-flow" action="">
                            <div class="row">
                                <div class="form-group col-md-1">
                                    <select class="form-control" name="type">
                                        <option value="">请选择资金大类</option>
                                        @foreach (config('user_asset.type') as $key => $value)
                                            <option value="{{ $key }}" {{ $key == request('type') ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-1">
                                    <select class="form-control" name="sub_type">
                                        <option value="">请选择资金子类</option>
                                        @foreach (config('user_asset.sub_type') as $key => $value)
                                            <option value="{{ $key }}" {{ $key == request('sub_type') ? 'selected' : '' }}> {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <input type="text" class="layui-input" name="user_id" placeholder="用户ID" value="{{ request('user_id') }}">
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" id="start-time" name="start_time" value="{{ request('start_time') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" id="end-time" name="end_time" value="{{ request('end_time') }}">
                                    </div>
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
                                <th>用户ID</th>
                                <th>资金大类</th>
                                <th>资金子类</th>
                                <th>发生金额</th>
                                <th>发生后可用金额</th>
                                <th>发生后冻结金额</th>
                                <th>关联交易号</th>
                                <th>发生时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($flow as $item)
                                <tr>
                                    <td>{{ $item->user_id }}</td>
                                    <td>{{ config('user_asset.type')[$item->type] }}</td>
                                    <td>{{ config('user_asset.sub_type')[$item->sub_type] }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->balance }}</td>
                                    <td>{{ $item->frozen }}</td>
                                    <td>{{ $item->trade_no }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $flow->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#export').click(function () {
            window.location.href = "{{ route('admin.user-asset-flow.export') }}?" + $('#search-flow').serialize();
        });

        layui.use(['layer'], function () {
        });
    </script>
@endsection
