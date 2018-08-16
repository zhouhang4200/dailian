<?php

namespace App\Http\Controllers\Front;

use App\Models\Game;
use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use App\Models\GameLevelingType;
use App\Models\Region;
use App\Models\Server;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Front
 */
class OrderController extends Controller
{
    /**
     * 首页待接单列表
     */
    public function index()
    {
        $regions = [];
        $servers = [];
        $gameLevelingTypes = [];

        if (request('game_id')) {
            $regions = Region::condition(['game_id' => request('game_id')])->get(['name', 'id']);
            $gameLevelingTypes = GameLevelingType::where('game_id', request('game_id'))->get(['name', 'id']);
        }

        if (request('region_id')) {
            $servers = Server::condition(['region_id' => request('region_id')])->get(['name', 'id']);
        }

        return view('front.order.index', [
            'orders' => GameLevelingOrder::condition(array_merge(request()->except('status'), ['status' => 1]))->paginate(20),
            'guest' => auth()->guard()->guest(),
            'games' => Game::all(),
            'regions' => $regions,
            'servers' => $servers,
            'gameLevelingTypes' => $gameLevelingTypes,
        ]);
    }

    /**
     * 待接单列表
     */
    public function waitList()
    {
        $regions = [];
        $servers = [];
        $gameLevelingTypes = [];

        if (request('game_id')) {
            $regions = Region::condition(['game_id' => request('game_id')])->get(['name', 'id']);
            $gameLevelingTypes = GameLevelingType::where('game_id', request('game_id'))->get(['name', 'id']);
        }

        if (request('region_id')) {
            $servers = Server::condition(['region_id' => request('region_id')])->get(['name', 'id']);
        }

        return view('front.order.wait-list', [
            'orders' => GameLevelingOrder::condition(array_merge(request()->except('status'), ['status' => 1]))->paginate(20),
            'guest' => auth()->guard()->guest(),
            'games' => Game::all(),
            'regions' => $regions,
            'servers' => $servers,
            'gameLevelingTypes' => $gameLevelingTypes,
        ]);
    }

    /**
     * 获取游戏服务器
     */
    public function getServers()
    {
        if (request()->ajax()) {
            return response()->ajaxSuccess('获取成功', Server::condition(['region_id' => request('region_id')])->get(['id', 'name']));
        }
    }

    /**
     * 接单列表视图
     */
    public function takeList()
    {
        return view('front.order.take-list', [
            'games' => Game::getAll(),
        ]);
    }

    /**
     * 接单列表数据
     * @return array
     */
    public function takeListData()
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
                'hour',
                'security_deposit',
                'efficiency_deposit',
                'created_at',
                'take_at',
                'player_phone',
            ]);

            // 写入状态描述
            $ordersArr[$key]['status_describe'] = $item->getStatusDescribe();
            // 隐藏部分密码
            $ordersArr[$key]['game_password'] = str_replace(substr($ordersArr[$key]['game_password'], -4, 4), '****', $ordersArr[$key]['game_password']);
            // 计算订单剩余代练时间
            $ordersArr[$key]['remaining_time'] = $item->getRemainingTime();
            // 计算订单获得金额 支付金额 手续费 利润 撤销发起人 仲裁发起人
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
     * @param $tradeNo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($tradeNo)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' => $tradeNo], 2)->firstOrFail();

        return view('front.order.show', [
            'order' => $order
        ]);
    }
}
