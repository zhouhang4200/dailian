<?php

namespace App\Http\Controllers\Front;

use DB;
use \Exception;
use App\Exceptions\NoSufficientBalanceException;
use App\Services\OrderService;
use App\Models\Game;
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
                ->paginate(20),
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
     * 查看验收图片
     * @param $tradeNO
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function applyCompleteImage($tradeNO)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' =>  $tradeNO])->with('applyComplete')->firstOrFail();

        $this->authorize('view', $order);

        if (is_null($order->applyComplete)) {
            return response()->ajaxFail('暂时没有图片');
        }

        return response()->ajaxSuccess('获取成功', view()->make('front.order.apply-complete-image', [
            'image' => $order->applyComplete->image,
        ])->render());
    }
}
