<?php

namespace App\Http\Controllers\Front\Finance;

use App\Models\BalanceWithdraw;
use App\Http\Controllers\Controller;

class BalanceWithdrawController extends Controller
{
    /**
     * 用户资金流水
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.finance.balance-withdraw.index', [
            'withdraws' => BalanceWithdraw::condition(array_merge(request()->except('user_id'), [
                'user_id' => request()->user()->parent_id]))->paginate(),
        ]);
    }

    /**
     * 提现导出
     */
    public function export()
    {
        $query = BalanceWithdraw::condition(array_merge(request()->except('user_id'), [
            'user_id' => request()->user()->parent_id]));

        return export([
            '提现单号',
            '提现金额',
            '状态',
            '备注',
            '创建时间',
        ], '我的提现', $query, function ($query, $out){
            $query->chunk(100, function ($items) use ($out) {
                foreach ($items as $item) {
                    $data = [
                        $item->trade_no . "\t",
                        $item->amount,
                        config('user_asset.withdraw_status')[$item->status],
                        $item->remark,
                        $item->created_at,
                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }
}
