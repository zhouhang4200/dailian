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
                    ->take(clientRSADecrypt(request('pay_password')), clientRSADecrypt(request('take_password')));
            } catch (Exception $exception) {
                return response()->ajaxFail($exception->getMessage());
            }
            DB::commit();
            return response()->ajaxSuccess('接单成功');
        }

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
