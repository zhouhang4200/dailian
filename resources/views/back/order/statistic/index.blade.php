@extends('back.layouts.app')

@section('title', ' | 订单统计')

@section('content')
    <div class="main-box">
        <div class="main-box-body clearfix">
            <div class="layui-tab layui-tab-brief" lay-filter="widgetTab">
                <ul class="layui-tab-title">
                    <li class="layui-this" lay-id="add">订单统计</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form id="search-flow" action="">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <select class="form-control" name="game_id">
                                        <option value="">游戏</option>
                                        @foreach ($games as $game)
                                            <option value="{{ $game->id }}" {{ $game->id == $gameId ? 'selected' : '' }}>{{ $game->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="take_user_id" placeholder="接单用户ID" value="{{ $takeUserId }}">
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off"  value="{{ $startDate }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="{{ $endDate }}">
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
                                <th>发布时间</th>
                                <th>发布单数</th>
                                <th>被接单数</th>
                                <th>已结算单数</th>
                                <th>已结算占比</th>
                                <th>已撤销单数</th>
                                <th>已撤销占比</th>
                                <th>已仲裁单数</th>
                                <th>已仲裁占比</th>
                                <th>完单平均所用时间</th>
                                <th>完单平均安全保证金</th>
                                <th>完单平均效率保证金</th>
                                <th>完单平均发单价格</th>
                                <th>完单总发单价格</th>
                                <th>结算平均收入</th>
                                <th>结算总收入</th>
                                <th>撤销平均支付</th>
                                <th>撤销总支付</th>
                                <th>撤销平均赔偿</th>
                                <th>撤销总赔偿</th>
                                <th>仲裁平均支付</th>
                                <th>仲裁总支付</th>
                                <th>仲裁平均赔偿</th>
                                <th>仲裁总赔偿</th>
                                <th>平均手续费</th>
                                <th>总手续费</th>
                                <th>平均利润</th>
                                <th>总利润</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($orderStatistics as $orderStatistic)
                                <tr>
                                    <td>{{ $orderStatistic->date }}</td>
                                    <td>{{ $orderStatistic->orderCount }}</td>
                                    <td>{{ $orderStatistic->takeCount }}</td>
                                    <td>{{ $orderStatistic->completeCount }}</td>
                                    <td>{{ toPercent($orderStatistic->completeCount, $orderStatistic->orderCount) }}</td>
                                    <td>{{ $orderStatistic->consultCount }}</td>
                                    <td>{{ toPercent($orderStatistic->consultCount, $orderStatistic->orderCount) }}</td>
                                    <td>{{ $orderStatistic->complainCount }}</td>
                                    <td>{{ toPercent($orderStatistic->complainCount, $orderStatistic->orderCount) }}</td>
                                    <td>{{ $orderStatistic->doneCount ? (sec2Time(intval(bcdiv($orderStatistic->doneTime, $orderStatistic->doneCount, 0))) ?: '--') : '--' }}</td>
                                    <td>{{ $orderStatistic->doneCount ? bcdiv($orderStatistic->doneSecurityDeposit, $orderStatistic->doneCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->doneCount ? bcdiv($orderStatistic->doneEfficiencyDeposit, $orderStatistic->doneCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->doneCount ? bcdiv($orderStatistic->doneAmount, $orderStatistic->doneCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->doneAmount }}</td>
                                    <td>{{ $orderStatistic->completeCount ? bcdiv($orderStatistic->completeIncome, $orderStatistic->completeCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->completeIncome }}</td>
                                    <td>{{ $orderStatistic->consultCount ? bcdiv($orderStatistic->consultExpend, $orderStatistic->consultCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->consultExpend }}</td>
                                    <td>{{ $orderStatistic->consultCount ? bcdiv($orderStatistic->consultIncome, $orderStatistic->consultCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->consultIncome }}</td>
                                    <td>{{ $orderStatistic->complainCount ? bcdiv($orderStatistic->complainExpend, $orderStatistic->complainCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->complainExpend }}</td>
                                    <td>{{ $orderStatistic->complainCount ? bcdiv($orderStatistic->complainIncome, $orderStatistic->complainCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->complainIncome }}</td>
                                    <td>{{ $orderStatistic->doneCount ? bcdiv($orderStatistic->poundageExpend, $orderStatistic->doneCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->poundageExpend }}</td>
                                    <td>{{ $orderStatistic->doneCount ? bcdiv($orderStatistic->completeIncome+$orderStatistic->consultIncome+$orderStatistic->complainIncome-$orderStatistic->consultExpend-$orderStatistic->complainExpend-$orderStatistic->poundageExpend+0, $orderStatistic->doneCount, 2)+0 : 0 }}</td>
                                    <td>{{ $orderStatistic->completeIncome+$orderStatistic->consultIncome+$orderStatistic->complainIncome-$orderStatistic->consultExpend-$orderStatistic->complainExpend-$orderStatistic->poundageExpend+0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="99">暂无数据</td>
                                </tr>
                            @endforelse
                            @if($total && $total->orderCount)
                            <tr>
                                <td>总计</td>
                                <td>{{ $total->orderCount }}</td>
                                <td>{{ $total->takeCount }}</td>
                                <td>{{ $total->completeCount }}</td>
                                <td>{{ toPercent($total->completeCount, $total->orderCount) }}</td>
                                <td>{{ $total->consultCount }}</td>
                                <td>{{ toPercent($total->consultCount, $total->orderCount) }}</td>
                                <td>{{ $total->complainCount }}</td>
                                <td>{{ toPercent($total->complainCount, $total->orderCount) }}</td>
                                <td>{{ $total->doneCount ? (sec2Time(intval(bcdiv($total->doneTime, $total->doneCount, 0))) ?: '--') : '--' }}</td>
                                <td>{{ $total->doneCount ? bcdiv($total->doneSecurityDeposit, $total->doneCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->doneCount ? bcdiv($total->doneEfficiencyDeposit, $total->doneCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->doneCount ? bcdiv($total->doneAmount, $total->doneCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->doneAmount }}</td>
                                <td>{{ $total->completeCount ? bcdiv($total->completeIncome, $total->completeCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->completeIncome }}</td>
                                <td>{{ $total->consultCount ? bcdiv($total->consultExpend, $total->consultCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->consultExpend }}</td>
                                <td>{{ $total->consultCount ? bcdiv($total->consultIncome, $total->consultCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->consultIncome }}</td>
                                <td>{{ $total->complainCount ? bcdiv($total->complainExpend, $total->complainCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->complainExpend }}</td>
                                <td>{{ $total->complainCount ? bcdiv($total->complainIncome, $total->complainCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->complainIncome }}</td>
                                <td>{{ $total->doneCount ? bcdiv($total->poundageExpend, $total->doneCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->poundageExpend }}</td>
                                <td>{{ $total->doneCount ? bcdiv($total->completeIncome+$total->consultIncome+$total->complainIncome-$total->consultExpend-$total->complainExpend-$total->poundageExpend+0, $total->doneCount, 2)+0 : 0 }}</td>
                                <td>{{ $total->completeIncome+$total->consultIncome+$total->complainIncome-$total->consultExpend-$total->complainExpend-$total->poundageExpend+0 }}</td>
                            </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $orderStatistics->appends([
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'take_user_id' => $takeUserId,
                            'game_id' => $gameId
                        ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#export').click(function () {
            window.location.href = "{{ route('admin.order-statistic.export') }}?" + $('#search-flow').serialize();
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
