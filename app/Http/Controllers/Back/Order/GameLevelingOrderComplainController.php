<?php

namespace App\Http\Controllers\Back\Order;

use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use App\Models\GameLevelingOrderComplain;
use App\Models\GameLevelingOrderLog;

/**
 * 代练订单仲裁
 * Class OrderController
 * @package App\Http\Controllers\Back
 */
class GameLevelingOrderComplainController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.order.game-leveling-order-complain.index', [
            'complainOrders' => GameLevelingOrderComplain::with('order')->condition(request()->all())->paginate(),
            'statusCount' => GameLevelingOrderComplain::selectRaw('status, count(1) as count')
            ->groupBy('status')->pluck('status', 'count')->toArray(),
        ]);
    }

    /**
     * 订单详情
     * @param $tradeNO
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($tradeNO)
    {
        return view('back.order.show', [
            'order' => GameLevelingOrder::where('trade_no', $tradeNO)->firstOrFail(),
        ]);
    }

    /**
     * 订单日志
     * @param $tradeNO
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function log($tradeNO)
    {
        return view('back.order.log', [
            'logs' => GameLevelingOrderLog::where('game_leveling_order_trade_no', $tradeNO)->get(),
        ]);
    }
}
