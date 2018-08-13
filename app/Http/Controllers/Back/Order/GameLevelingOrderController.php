<?php

namespace App\Http\Controllers\Back\Order;

use App\Models\GameLevelingOrder;
use App\Models\GameLevelingOrderLog;
use App\Http\Controllers\Controller;

/**
 * 订单
 * Class OrderController
 * @package App\Http\Controllers\Back
 */
class GameLevelingOrderController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.order.game-leveling-order.index', [
            'orders' => GameLevelingOrder::getOrderByCondition(request()->all())->paginate(),
        ]);
    }

    /**
     * 订单详情
     * @param $tradeNO
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($tradeNO)
    {
        return view('back.order.game-leveling-order.show', [
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
        return view('back.order.game-leveling-order.log', [
            'logs' => GameLevelingOrderLog::where('game_leveling_order_trade_no', $tradeNO)->get(),
        ]);
    }
}
