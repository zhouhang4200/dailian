<?php

namespace App\Http\Controllers\Front;

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
    public function index()
    {

    }

    /**
     * 接单方视图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function take()
    {
        return view('front.order.take', [
            'games' => Game::getAll(),
        ]);
    }

    /**
     * 获取接单方数据列表
     * @return array
     */
    public function takeData()
    {
        $orders = GameLevelingOrder::getOrderByCondition(request()->all(), 2)->paginate(50);

        // 对获取数据进行处理后响应
        $ordersArr = [];
        foreach ($orders->items() as $key => $item) {

            $itemOrigin = $item->toArray();

            // 保留需要的数据
            $ordersArr[$key] = array_only($itemOrigin, [
                'id',
                'trade_no',
                'status',
                'game_name',
                'region_name',
                'server_name',
                'title',
                'game_account',
                'game_password',
                'game_role',
                'amount',
                'day',
                'hours',
                'security_deposit',
                'efficiency_deposit',
                'created_at',
                'take_at',
                'payer_phone',
            ]);

            // 写入状态描述
            $ordersArr[$key]['status_describe'] = $item->getStatusDescribe();
            // 隐藏部分密码
            $ordersArr[$key]['game_password'] = str_replace(substr($ordersArr[$key]['game_password'], -4, 4), '****', $ordersArr[$key]['game_password']);
            // 计算订单剩余代练时间
            $ordersArr[$key]['remaining_time'] = $item->getRemainingTime();
            // 计算订单获得金额 支付金额
            $ordersArr[$key]['income_amount'] = $item->getIncomeAmount();
            $ordersArr[$key]['expend_amount'] = $item->getExpendAmount();
            $ordersArr[$key]['poundage'] = $item->getPoundage();;
            $ordersArr[$key]['profit'] = $item->getProfit();
            $ordersArr[$key]['consult_initiator'] = $item->getConsultInitiator();
            $ordersArr[$key]['complain_initiator'] = $item->getComplainInitiator();
        }

        return [
            'code' => 0,
            'msg' => '',
            'count' => $orders->total(),
            'data' =>  $ordersArr,
        ];
    }

    /**
     * 接单人查看订单详情
     * @param $tradeNO
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function takeShow($tradeNO)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' => $tradeNO], 2)->firstOrFail();

        return view('front.order.take_detail', [
            'order' => $order
        ]);
    }


    public function send()
    {
        return view('front.order.send');
    }

    public function sendData()
    {

    }

    public function sendShow()
    {

    }
}
