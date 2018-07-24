@extends('back.layouts.app')

@section('title', ' | 用户资金日报表')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">用户资金日报表</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form id="search-flow" action="">
                            <div class="row">

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
                                <th>统计日期</th>
                                <th>用户ID</th>
                                <th>期初金额</th>
                                <th>充值金额</th>
                                <th>收入金额</th>
                                <th>提现金额</th>
                                <th>支出金额</th>
                                <th>理论结余</th>
                                <th>实际结余</th>
                                <th>差异</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($report as $item)
                                <tr>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->user_id }}</td>
                                    <td>{{ $item->getOpeningBalance() }}</td>
                                    <td>{{ $item->recharge }}</td>
                                    <td>{{ $item->income }}</td>
                                    <td>{{ $item->withdraw }}</td>
                                    <td>{{ $item->expend }}</td>
                                    <td>{{ $item->getTheoryBalance() }}</td>
                                    <td>{{ $item->getRealityBalance() }}</td>
                                    <td>{{ $item->getDifference() }}</td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        {{ $report->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#export').click(function () {
            window.location.href = "{{ route('admin.user-finance-report-day.export') }}?" + $('#search-flow').serialize();
        });

        layui.use(['layer'], function () {


        });

    </script>
@endsection
