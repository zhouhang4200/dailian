<?php

namespace App\Http\Controllers\Back\Order;

use DB;
use App\Models\Game;
use App\Models\GameLevelingOrder;
use App\Models\OrderStatistic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 *  订单统计
 * Class StatisticController
 * @package App\Http\Controllers\Back\Order
 */
class StatisticController extends Controller
{
    /**
     *  后台订单统计
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $startDate = request('start_date');
        $endDate = request('end_date');
        $gameId = request('game_id');
        $takeUserId = request('take_user_id');
        $filters = compact('startDate', 'endDate', 'gameId', 'takeUserId');

        $games = Game::get();

        $orderStatistics = OrderStatistic::adminFilter($filters)
            ->select(DB::raw('
                date,
                count(trade_no) as orderCount,
                sum(case when status in (2, 8, 9, 10) then 1 else 0 end) as takeCount,
                sum(case when status in (8, 9, 10) then 1 else 0 end) as doneCount,
                sum(case when status = 10 then 1 else 0 end) as completeCount,
                sum(case when status in (8, 9, 10) then unix_timestamp(order_finished_at)-unix_timestamp(order_created_at) else 0 end) as doneTime,
                sum(case when status in (8, 9, 10) then security_deposit else 0 end) as doneSecurityDeposit,
                sum(case when status in (8, 9, 10) then efficiency_deposit else 0 end) as doneEfficiencyDeposit,
                sum(case when status in (8, 9, 10) then amount else 0 end) as doneAmount,
                sum(case when status = 8 then 1 else 0 end) as consultCount,
                sum(case when status = 9 then 1 else 0 end) as complainCount,
                sum(case when status = 10 then amount else 0 end) as completeIncome,
                sum(case when status = 8 then consult_complain_amount else 0 end) as consultIncome,
                sum(case when status = 9 then consult_complain_amount else 0 end) as complainIncome,
                sum(case when status = 8 then consult_complain_deposit else 0 end) as consultExpend,
                sum(case when status = 9 then consult_complain_deposit else 0 end) as complainExpend,
                sum(take_poundage) as poundageExpend
            '))
            ->groupBy('date')
            ->latest('date')
            ->paginate(10);

        $total = OrderStatistic::adminFilter($filters)
            ->select(DB::raw('
                count(trade_no) as orderCount,
                sum(case when status in (2, 8, 9, 10) then 1 else 0 end) as takeCount,
                sum(case when status in (8, 9, 10) then 1 else 0 end) as doneCount,
                sum(case when status = 10 then 1 else 0 end) as completeCount,
                sum(case when status in (8, 9, 10) then unix_timestamp(order_finished_at)-unix_timestamp(order_created_at) else 0 end) as doneTime,
                sum(case when status in (8, 9, 10) then security_deposit else 0 end) as doneSecurityDeposit,
                sum(case when status in (8, 9, 10) then efficiency_deposit else 0 end) as doneEfficiencyDeposit,
                sum(case when status in (8, 9, 10) then amount else 0 end) as doneAmount,
                sum(case when status = 8 then 1 else 0 end) as consultCount,
                sum(case when status = 9 then 1 else 0 end) as complainCount,
                sum(case when status = 10 then amount else 0 end) as completeIncome,
                sum(case when status = 8 then consult_complain_amount else 0 end) as consultIncome,
                sum(case when status = 9 then consult_complain_amount else 0 end) as complainIncome,
                sum(case when status = 8 then consult_complain_deposit else 0 end) as consultExpend,
                sum(case when status = 9 then consult_complain_deposit else 0 end) as complainExpend,
                sum(take_poundage) as poundageExpend
            '))
            ->first();

        return view('back.order.statistic.index', compact('orderStatistics', 'startDate', 'endDate', 'total', 'gameId', 'takeUserId', 'games'));
    }

    /**
     * 导出
     */
    public function export()
    {
        $startDate = request('start_date');
        $endDate = request('end_date');
        $gameId = request('game_id');
        $takeUserId = request('take_user_id');
        $filters = compact('startDate', 'endDate', 'gameId', 'takeUserId');

        $query = OrderStatistic::adminFilter($filters)
            ->select(DB::raw('
                date,
                count(trade_no) as orderCount,
                sum(case when status in (2, 8, 9, 10) then 1 else 0 end) as takeCount,
                sum(case when status in (8, 9, 10) then 1 else 0 end) as doneCount,
                sum(case when status = 10 then 1 else 0 end) as completeCount,
                sum(case when status in (8, 9, 10) then unix_timestamp(order_finished_at)-unix_timestamp(order_created_at) else 0 end) as doneTime,
                sum(case when status in (8, 9, 10) then security_deposit else 0 end) as doneSecurityDeposit,
                sum(case when status in (8, 9, 10) then efficiency_deposit else 0 end) as doneEfficiencyDeposit,
                sum(case when status in (8, 9, 10) then amount else 0 end) as doneAmount,
                sum(case when status = 8 then 1 else 0 end) as consultCount,
                sum(case when status = 9 then 1 else 0 end) as complainCount,
                sum(case when status = 10 then amount else 0 end) as completeIncome,
                sum(case when status = 8 then consult_complain_amount else 0 end) as consultIncome,
                sum(case when status = 9 then consult_complain_amount else 0 end) as complainIncome,
                sum(case when status = 8 then consult_complain_deposit else 0 end) as consultExpend,
                sum(case when status = 9 then consult_complain_deposit else 0 end) as complainExpend,
                sum(take_poundage) as poundageExpend
            '))
            ->groupBy('date')
            ->latest('date');

        return export([
            '发布时间',
            '发布单数',
            '被接单数',
            '已结算单数',
            '已结算占比',
            '已撤销单数',
            '已撤销占比',
            '已仲裁单数',
            '已仲裁占比',
            '完单平均所用时间',
            '完单平均安全保证金',
            '完单平均效率保证金',
            '完单平均发单价格',
            '完单总发单价格',
            '结算平均收入',
            '结算总收入',
            '撤销平均支付',
            '撤销总支付',
            '撤销平均赔偿',
            '撤销总赔偿',
            '仲裁平均支付',
            '仲裁总支付',
            '仲裁平均赔偿',
            '仲裁总赔偿',
            '打手平均手续费',
            '打手总手续费',
            '打手平均利润',
            '打手总利润'
        ], '订单统计', $query, function($query, $out) {
            $query->chunk(100, function ($orderStatistics) use ($out) {
                foreach ($orderStatistics as $orderStatistic) {
                    $data = [
                        $orderStatistic->date,
                        $orderStatistic->orderCount,
                        $orderStatistic->takeCount,
                        $orderStatistic->completeCount,
                        toPercent($orderStatistic->completeCount, $orderStatistic->orderCount),
                        $orderStatistic->consultCount,
                        toPercent($orderStatistic->consultCount, $orderStatistic->orderCount),
                        $orderStatistic->complainCount,
                        toPercent($orderStatistic->complainCount, $orderStatistic->orderCount),
                        $orderStatistic->doneCount ? sec2Time(intval(bcdiv($orderStatistic->doneTime, $orderStatistic->doneCount, 0))) : 0,
                        $orderStatistic->doneCount ? bcdiv($orderStatistic->doneSecurityDeposit, $orderStatistic->doneCount, 2)+0 : 0,
                        $orderStatistic->doneCount ? bcdiv($orderStatistic->doneEfficiencyDeposit, $orderStatistic->doneCount, 2)+0 : 0,
                        $orderStatistic->doneCount ? bcdiv($orderStatistic->doneAmount, $orderStatistic->doneCount, 2)+0 : 0,
                        $orderStatistic->doneAmount,
                        $orderStatistic->completeCount ? bcdiv($orderStatistic->completeIncome, $orderStatistic->completeCount, 2)+0 : 0,
                        $orderStatistic->completeIncome,
                        $orderStatistic->consultCount ? bcdiv($orderStatistic->consultExpend, $orderStatistic->consultCount, 2)+0 : 0,
                        $orderStatistic->consultExpend,
                        $orderStatistic->consultCount ? bcdiv($orderStatistic->consultIncome, $orderStatistic->consultCount, 2)+0 : 0,
                        $orderStatistic->consultIncome,
                        $orderStatistic->complainCount ? bcdiv($orderStatistic->complainExpend, $orderStatistic->complainCount, 2)+0 : 0,
                        $orderStatistic->complainExpend,
                        $orderStatistic->complainCount ? bcdiv($orderStatistic->complainIncome, $orderStatistic->complainCount, 2)+0 : 0,
                        $orderStatistic->complainIncome,
                        $orderStatistic->doneCount ? bcdiv($orderStatistic->poundageExpend, $orderStatistic->doneCount, 2)+0 : 0,
                        $orderStatistic->poundageExpend,
                        $orderStatistic->doneCount ? bcdiv($orderStatistic->completeIncome+$orderStatistic->consultIncome+$orderStatistic->complainIncome-$orderStatistic->consultExpend-$orderStatistic->complainExpend-$orderStatistic->poundageExpend+0, $orderStatistic->doneCount, 2)+0 : 0,
                        $orderStatistic->completeIncome+$orderStatistic->consultIncome+$orderStatistic->complainIncome-$orderStatistic->consultExpend-$orderStatistic->complainExpend-$orderStatistic->poundageExpend+0
                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }
}
