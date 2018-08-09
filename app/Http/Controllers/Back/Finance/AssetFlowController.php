<?php

namespace App\Http\Controllers\Back\Finance;

use App\Models\UserAssetFlow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetFlowController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.finance.asset-flow.index', [
            'flows' => UserAssetFlow::condition(request()->all())->paginate()
        ]);
    }

    /**
     * 导出报表
     */
    public function export()
    {
        $query = UserAssetFlow::condition(request()->all());

        return export([
            '用户ID',
            '资金大类',
            '资金子类',
            '发生金额',
            '发生后余额',
            '发生后冻结',
            '关联交易号',
            '发生时间',
        ], '平台资金日报表', $query, function ($query, $out) {
            $query->chunk(100, function ($items) use ($out) {
                foreach ($items as $item) {
                    $data = [
                        $item->user_id,
                        config('user_asset.type')[$item->type],
                        config('user_asset.sub_type')[$item->sub_type],
                        $item->amount,
                        $item->balance,
                        $item->frozen,
                        $item->trade_no,
                        $item->created_at,
                    ];
                    fputcsv($out, $data);
                }
            });
        });
    }
}
