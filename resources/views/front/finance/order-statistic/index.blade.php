@extends('front.layouts.app')

@section('title', '财务 - 订单统计')

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
                        <label class="layui-form-mid">发布时间：</label>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="start_date" name="start_date" autocomplete="off" value="{{ $startDate }}" placeholder="开始时间">
                        </div>
                        <div class="layui-input-inline" style="">
                            <input type="text" class="layui-input" id="end_date" name="end_date" autocomplete="off" value="{{ $endDate }}" placeholder="结束时间">
                        </div>
                        <button class="qs-btn layui-btn-normal" type="submit">查询</button>
                        <button class="qs-btn layui-btn-normal" type="button" id="export">导出</button>
                    </div>
                </div>
            </form>

            <table class="layui-table" lay-size="sm">
                <colgroup>
                    <col width="150">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>发布时间</th>
                    <th>接单数</th>
                    <th>已结算单数</th>
                    <th>已结算占比</th>
                    <th>已撤销单数</th>
                    <th>已仲裁单数</th>
                    <th>已结算单获得金额</th>
                    <th>撤销/仲裁获得金额</th>
                    <th>撤销/仲裁支付赔偿</th>
                    <th>支出手续费</th>
                    <th>利润</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orderStatistics as $orderStatistic)
                    <tr>
                        <td>{{ $orderStatistic->date }}</td>
                        <td>{{ $orderStatistic->takeCount }}</td>
                        <td>{{ $orderStatistic->completeCount }}</td>
                        <td>{{ toPercent($orderStatistic->completeCount, $orderStatistic->orderCount) }}</td>
                        <td>{{ $orderStatistic->consultCount }}</td>
                        <td>{{ $orderStatistic->complainCount }}</td>
                        <td>{{ $orderStatistic->completeIncome }}</td>
                        <td>{{ $orderStatistic->otherIncome }}</td>
                        <td>{{ $orderStatistic->otherExpend }}</td>
                        <td>{{ $orderStatistic->poundageExpend }}</td>
                        <td>{{ $orderStatistic->completeIncome+$orderStatistic->otherIncome-$orderStatistic->otherExpend-$orderStatistic->poundageExpend }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="999">暂时没有数据</td>
                    </tr>
                @endforelse
                @if($total && ! empty($total->takeCount))
                    <tr>
                        <td>总计</td>
                        <td>{{ $total->takeCount }}</td>
                        <td>{{ $total->completeCount }}</td>
                        <td>{{ toPercent($total->completeCount, $total->orderCount) }}</td>
                        <td>{{ $total->consultCount }}</td>
                        <td>{{ $total->complainCount }}</td>
                        <td>{{ $total->completeIncome }}</td>
                        <td>{{ $total->otherIncome }}</td>
                        <td>{{ $total->otherExpend }}</td>
                        <td>{{ $total->poundageExpend }}</td>
                        <td>{{ $total->completeIncome+$total->otherIncome-$total->otherExpend-$total->poundageExpend }}</td>
                    </tr>
                @endif
                </tbody>
            </table>

            {{ $orderStatistics->appends([
                'start_date' => $startDate,
                'end_date' => $endDate
            ])->links() }}
            @endsection
        </div>
    </div>
@section('js')
    <script>
        $('#export').click(function () {
            window.location.href = "{{ route('finance.order-statistic.export') }}?" + $('#search').serialize();
        });

        layui.use(['form', 'layedit', 'laydate'], function(){
            var laydate = layui.laydate;
            var form = layui.form;

            laydate.render({
                elem: '#start_date'
            });

            laydate.render({
                elem: '#end_date'
            });
        });
    </script>
@endsection
