<?php

namespace App\Http\Controllers\Front;

use App\Models\Game;
use App\Models\GameLevelingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {

    }

    /**
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

            // 写入状态中文
            $ordersArr[$key]['status_des'] = $item->getOrderStatusDes();

            // 隐藏部分密码
            $ordersArr[$key]['game_password'] = str_replace(substr($ordersArr[$key]['game_password'], -4, 4), '****', $ordersArr[$key]['game_password']);

            // 计算订单剩余代练时间
            $ordersArr[$key]['remaining_time'] = sec2Time(Carbon::parse($item->take_at)
                ->addDays($item->day)
                ->addHours($item->hours)
                ->diffInSeconds(Carbon::now()));

            // 计算订单获得金额 支付金额
            $ordersArr[$key]['get_amount'] = 0;
            $ordersArr[$key]['pay_amount'] = 0;
            $ordersArr[$key]['poundage'] = 0;
            $ordersArr[$key]['profit'] = 0;
            $ordersArr[$key]['complain'] = 0;
            $ordersArr[$key]['consult'] = 0;

            // 仲裁完成
            if (optional($item->complain)->status == 3) {
                $ordersArr[$key]['get_amount'] += $item->complain->amount;
                $ordersArr[$key]['pay_amount'] = bcadd($item->complain->security_deposit, $item->complain->efficiency_deposit);
                $ordersArr[$key]['poundage'] = $item->complain->poundage;
                $ordersArr[$key]['complain'] = $item->complain->parent_user_id == request()->user()->parent_user_id ? 1 : 2;
            }
            // 协商完成
            if (optional($item->consult)->consult == 3) {
                $ordersArr[$key]['get_amount'] += $item->complain->amount;
                $ordersArr[$key]['pay_amount'] = bcadd($item->complain->security_deposit, $item->complain->efficiency_deposit);
                $ordersArr[$key]['consult'] = $item->complain->parent_user_id == request()->user()->parent_user_id ? 1 : 2;
            }
            // 正常结算
            if ($item->status == 10) {
                $ordersArr[$key]['get_amount'] += $item->amount;
            }
            // 计算订单利润
            $ordersArr[$key]['profit'] = $ordersArr[$key]['get_amount'] - $ordersArr[$key]['pay_amount'];
        }

        return [
            'code' => 0,
            'msg' => '',
            'count' => $orders->total(),
            'data' =>  $ordersArr,
        ];
    }

    public function takeShow()
    {

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
