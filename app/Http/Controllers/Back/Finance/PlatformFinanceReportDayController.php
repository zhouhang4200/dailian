<?php

namespace App\Http\Controllers\Back\Finance;

use \App\Models\PlatformFinanceReportDay;
use App\Http\Controllers\Controller;

/**
 * 平台资金日报表
 * Class PlatformFinanceReportDay
 * @package App\Http\Controllers\Back
 */
class PlatformFinanceReportDayController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.finance.platform_finance_report_day.index', [
           'report' => PlatformFinanceReportDay::condition(request()->all())->paginate()
        ]);
    }

    /**
     * 导出报表
     */
    public function export()
    {
        $query = PlatformFinanceReportDay::condition(request()->all());

        return export([
            '统计日期',
            '期初金额',
            '充值金额',
            '收入金额',
            '提现金额',
            '支出金额',
            '理论结余',
            '实际结余',
            '差异',
        ], '平台资金日报表', $query, function ($query, $out){
            $query->chunk(100, function ($items) use ($out) {
                foreach ($items as $item) {
                    $data = [
                        $item->date,
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
