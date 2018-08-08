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
        return view('front.order.index', [
            'orders' => GameLevelingOrder::getOrderByCondition(array_merge(request()->except('status'), ['status' => 1]))
                ->paginate(),
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

    }
}
