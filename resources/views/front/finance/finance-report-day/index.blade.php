@extends('front.layouts.app')

@section('title', '财务 - 资金日报')

@section('css')
    <style>
        .layui-form-item .layui-inline {
            margin-bottom: 5px;
            margin-right: 5px;
        }
        .layui-form-mid {
            margin-right: 4px;
        }
    </style>
@endsection

@section('main')
    <div class="layui-card qs-text">
        <div class="layui-card-body">
            <form class="layui-form" id="search">
                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-mid">时间：</label>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="time-start" name="start_time" autocomplete="off" value="{{ request('start_time') }}" placeholder="开始时间">
                        </div>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="time-end" name="end_time"  autocomplete="off" value="{{ request('end_time') }}" placeholder="结束时间">
                        </div>
                        <button class="qs-btn layui-btn-normal" type="submit">查询</button>
                        <button class="qs-btn layui-btn-normal" type="button" id="export">导出</button>
                    </div>
                </div>
            </form>


            <table class="layui-table" lay-size="sm">
                <thead>
                <tr>
                    <th>统计日期</th>
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
                @forelse ($reports as $item)
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
            {{ $reports->appends(request()->all())->links() }}
        </div>
    </div>
@endsection

@section('js')
    <script>
        layui.use(['laydate', 'form'], function () {
            var laydate = layui.laydate;

            laydate.render({elem: '#time-start'});
            laydate.render({elem: '#time-end'});
        });

        $('#export').click(function () {
            window.location.href = "{{ route('finance.finance-report-day.export') }}?" + $('#search').serialize();
        });
    </script>
@endsection
