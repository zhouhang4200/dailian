<?php

namespace App\Http\Controllers\Front;

use App\Models\Game;
use App\Services\OrderService;
use DB;
use \Exception;
use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;


/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Front
 */
class OrderController extends Controller
{
    /**
     * 待接单列表
     */
    public function index()
    {
        return view('front.order.index', [
            'orders' => GameLevelingOrder::getOrderByCondition(array_merge(request()->except('status'), ['status' => 1]))
                ->paginate(1),
            'games' => Game::getAll(),
            'guest' => auth()->guard()->guest(),
        ]);
    }

    /**
     * 查看待接单详情
     */
    public function show()
    {

    }

    /**
     * 接单
     */
    public function take()
    {
        if (auth()->guard()->guest()) {
            return response()->ajaxFail('请先登录再接单');
        } else {
            DB::beginTransaction();
            try {
                OrderService::init(request()->user()->id, request('trade_no'))
                    ->take(clientRSADecrypt(request('payment_password')), clientRSADecrypt(request('take_password')));
            } catch (Exception $exception) {
                return response()->ajaxFail($exception->getMessage());
            }
            DB::commit();
            return response()->ajaxSuccess('接单成功');
        }

    }
}
