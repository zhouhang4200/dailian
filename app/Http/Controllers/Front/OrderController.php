<?php

namespace App\Http\Controllers\Front;

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
     */
    public function applyCompleteImage($tradeNO)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' =>  $tradeNO])->with('applyComplete')->firstOrFail();

        $this->authorize('view', $order);

        if (is_null($order->applyComplete)) {
            return response()->ajaxFail('暂时没有图片');
        }

        return response()->ajaxSuccess('获取成功', view()->make('front.order.apply_complete_image', [
            'image' => $order->applyComplete->image,
        ])->render());
    }
}
