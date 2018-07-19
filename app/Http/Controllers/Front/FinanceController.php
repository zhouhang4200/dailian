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

    public function assetFlowExport()
    {
        $query = UserAssetFlow::condition(request()->all());
        export([
            '天猫订单号',
            '内部订单号',
            '游戏',
            '订单状态',
            '结账状态',
            '最终支付金额',
            '代练结算时间',
            '结账时间',
        ], '财务订单导出', $query, function ($query, $out){
            $query->chunk(100, function ($items) use ($out) {

                foreach ($items as $item) {
                    $data = [

                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }
}
