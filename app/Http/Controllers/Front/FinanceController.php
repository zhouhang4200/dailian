<?php

namespace App\Http\Controllers\Front;

use App\Models\UserAssetFlow;
use App\Http\Controllers\Controller;

/**
 * Class FinanceController
 *
 * @package App\Http\Controllers\Front
 */
class FinanceController extends Controller
{
    /**
     * 用户资金流水
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.finance.index', [
           'assetFlow' => UserAssetFlow::condition(request()->all())->paginate()
        ]);
    }

    /**
     * 资金流水导出
     */
    public function assetFlowExport()
    {
        $query = UserAssetFlow::condition(request()->all());

        return export([
            '流水号',
            '交易单号',
            '类型',
            '变动金额',
            '账户余额',
            '说明',
            '时间',
        ], '资金流水', $query, function ($query, $out){
            $query->chunk(100, function ($items) use ($out) {
                foreach ($items as $item) {
                    $data = [
                        $item->id,
                        $item->trade_no . "\t",
                        config('user_asset.type')[$item->type],
                        $item->amount,
                        $item->balance,
                        config('user_asset.sub_type')[$item->sub_type],
                        $item->created_at,
                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }
}
