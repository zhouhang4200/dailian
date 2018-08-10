<?php

namespace App\Http\Controllers\Front\Finance;

use App\Models\UserFinanceReportDay;
use App\Http\Controllers\Controller;

/**
 * Class FinanceReportDayController
 * @package App\Http\Controllers\Front\Finance
 */
class FinanceReportDayController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.finance.finance-report-day.index', [
            'reports' => UserFinanceReportDay::condition(request()->all())->paginate()
        ]);
    }

    /**
     * 导出报表
     */
    public function export()
    {
        $query = UserFinanceReportDay::condition(request()->all());

        return export([
            '统计日期',
            '用户ID',
            '期初金额',
            '充值金额',
            '收入金额',
            '提现金额',
            '支出金额',
            '理论结余',
            '实际结余',
            '差异',
        ],
            '用户资金日报表', $query, function ($query, $out){
            $query->chunk(100, function ($items) use ($out) {
                foreach ($items as $item) {
                    $data = [
                        $item->date,
                        $item->user_id,
                        $item->getOpeningBalance(),
                        $item->recharge,
                        $item->income,
                        $item->withdraw,
                        $item->expend,
                        $item->getTheoryBalance(),
                        $item->getRealityBalance(),
                        $item->getDifference(),
                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }
}
