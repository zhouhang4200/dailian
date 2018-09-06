<?php

namespace App\Http\Controllers\Front\Finance;

use Auth;
use DB;
use App\Models\OrderStatistic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 *  订单统计
 * Class OrderStatisticController
 * @package App\Http\Controllers\Front\Finance
 */
class OrderStatisticController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $startDate = request('start_date');
        $endDate = request('end_date');
        $filters = compact('startDate', 'endDate');

        $orderStatistics = OrderStatistic::filter($filters)
            ->select(DB::raw('
                date,
                take_user_id,
                take_parent_user_id,
                count(trade_no) as orderCount,
                sum(case when status in (2, 8, 9, 10) then 1 else 0 end) as takeCount,
                sum(case when status = 10 then 1 else 0 end) as completeCount,
                sum(case when status = 8 then 1 else 0 end) as consultCount,
                sum(case when status = 9 then 1 else 0 end) as complainCount,
                sum(case when status = 10 then amount else 0 end) as completeIncome,
                sum(case when status in (8, 9) then consult_complain_amount else 0 end) as otherIncome,
                sum(case when status in (8, 9) then consult_complain_deposit else 0 end) as otherExpend,
                sum(take_poundage) as poundageExpend
            '))
            ->where('take_parent_user_id', Auth::user()->parent_id)
            ->groupBy('date')
            ->latest('date')
            ->paginate(10);

        $total = OrderStatistic::filter($filters)
            ->select(DB::raw('
                count(trade_no) as orderCount,
                sum(case when status in (2, 8, 9, 10) then 1 else 0 end) as takeCount,
                sum(case when status = 10 then 1 else 0 end) as completeCount,
                sum(case when status = 8 then 1 else 0 end) as consultCount,
                sum(case when status = 9 then 1 else 0 end) as complainCount,
                sum(case when status = 10 then amount else 0 end) as completeIncome,
                sum(case when status in (8, 9) then consult_complain_amount else 0 end) as otherIncome,
                sum(case when status in (8, 9) then consult_complain_deposit else 0 end) as otherExpend,
                sum(take_poundage) as poundageExpend
            '))
            ->where('take_parent_user_id', Auth::user()->parent_id)
            ->first();

        return view('front.finance.order-statistic.index', compact('orderStatistics', 'startDate', 'endDate', 'total'));
    }

    /**
     * 导出
     */
    public function export()
    {
        $startDate = request('start_date');
        $endDate = request('end_date');
        $filters = compact('startDate', 'endDate');

        $query = OrderStatistic::filter($filters)
            ->select(DB::raw('
                date,
                take_user_id,
                take_parent_user_id,
                count(trade_no) as orderCount,
                sum(case when status in (2, 8, 9, 10) then 1 else 0 end) as takeCount,
                sum(case when status = 10 then 1 else 0 end) as completeCount,
                sum(case when status = 8 then 1 else 0 end) as consultCount,
                sum(case when status = 9 then 1 else 0 end) as complainCount,
                sum(case when status = 10 then amount else 0 end) as completeIncome,
                sum(case when status in (8, 9) then consult_complain_amount else 0 end) as otherIncome,
                sum(case when status in (8, 9) then consult_complain_deposit else 0 end) as otherExpend,
                sum(take_poundage) as poundageExpend
            '))
            ->where('take_parent_user_id', Auth::user()->parent_id)
            ->groupBy('date')
            ->latest('date');

//        $total = OrderStatistic::filter($filters)
//            ->select(DB::raw('
//                count(trade_no) as orderCount,
//                sum(case when status in (2, 8, 9, 10) then 1 else 0 end) as takeCount,
//                sum(case when status = 10 then 1 else 0 end) as completeCount,
//                sum(case when status = 8 then 1 else 0 end) as consultCount,
//                sum(case when status = 9 then 1 else 0 end) as complainCount,
//                sum(case when status = 10 then amount else 0 end) as completeIncome,
//                sum(case when status in (8, 9) then consult_complain_amount else 0 end) as otherIncome,
//                sum(case when status in (8, 9) then consult_complain_deposit else 0 end) as otherExpend,
//                sum(take_poundage) as poundageExpend
//            '))
//            ->where('take_parent_user_id', Auth::user()->parent_id)
//            ->first();

        return export([
            '发布时间', '被接单数', '已结算单数', '已结算占比', '已撤销单数', '已仲裁单数', '以结算获得金额', '撤销/仲裁获得金额', '撤销/仲裁支付赔偿', '支出手续费', '利润'
        ], '订单统计', $query, function($query, $out) {
            $query->chunk(100, function ($orderStatistics) use ($out) {
                foreach ($orderStatistics as $orderStatistic) {
                    $data = [
                        $orderStatistic->date,
                        $orderStatistic->takeCount,
                        $orderStatistic->completeCount,
                        toPercent($orderStatistic->completeCount, $orderStatistic->orderCount),
                        $orderStatistic->consultCount,
                        $orderStatistic->complainCount,
                        $orderStatistic->completeIncome,
                        $orderStatistic->otherIncome,
                        $orderStatistic->otherExpend,
                        $orderStatistic->poundageExpend,
                        $orderStatistic->completeIncome+$orderStatistic->otherIncome-$orderStatistic->otherExpend-$orderStatistic->poundageExpend
                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }
}
