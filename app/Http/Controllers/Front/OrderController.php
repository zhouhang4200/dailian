<?php

namespace App\Http\Controllers\Front;

use App\Models\Game;
use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use App\Models\GameLevelingType;
use App\Models\Region;
use App\Models\Server;
use App\Services\OrderService;

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

        $existOrder = GameLevelingOrder::condition(['status' => 1])->first();

        $orders = [];
        if ($existOrder) {
            $orders = GameLevelingOrder::searchCondition(array_merge(request()->except('status'), ['status' => 1]))
                ->orderBy('top_at', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(20);
        }

//            ->explain();
//        dd($orders);

        return view('front.order.index', [
            'orders' => $orders,
            'guest' => auth()->guard()->guest(),
            'games' => Game::getAll(),
            'regions' => $regions,
            'servers' => $servers,
            'gameLevelingTypes' => $gameLevelingTypes,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexDetail()
    {
        $detail = GameLevelingOrder::searchCondition(['trade_no' => request('trade_no')])
            ->with('game')
            ->get()
            ->toArray();

        unset($detail[0]['id']);
        $detail[0]['top'] = strlen($detail[0]['take_order_password']) ? 1 : 2;
        $detail[0]['private'] = strlen($detail[0]['take_order_password']) ? 1 : 2;
        $detail[0]['icon'] = $detail[0]['game']['icon'];

        unset($detail[0]['id']);
        unset($detail[0]['game_id']);
        unset($detail[0]['game']);
//        unset($detail[0]['take_order_password']);


        return response()->json(view()->make('front.order.index-detail', [
            'detail' => $detail[0] ?? []
        ])->render());
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

        $existOrder = GameLevelingOrder::condition(['status' => 1])->first();

        $orders = [];
        if ($existOrder) {
            $orders = GameLevelingOrder::searchCondition(array_merge(request()->except('status'), ['status' => 1]))
                ->orderBy('top_at', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(20);
        }

        return view('front.order.wait-list', [
            'orders' => $orders,
            'guest' => auth()->guard()->guest(),
            'games' => Game::getAll(),
            'regions' => $regions,
            'servers' => $servers,
            'gameLevelingTypes' => $gameLevelingTypes,
        ]);
    }

    /**
     * 获取游戏区
     */
    public function gameRegions()
    {
        if (request()->ajax()) {
            return response()->ajaxSuccess('获取成功', Region::condition(['game_id' => request('game_id')])->get(['id', 'name']));
        }
    }

    /**
     * 获取游戏服务器
     */
    public function gameServers()
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

        # 对获取数据进行处理后响应
        $ordersArr = $this->handleOrderList($orders);

        return [
            'code' => 0,
            'msg' => '',
            'count' => $orders->total(),
            'data' =>  $ordersArr,
        ];
    }

    /**
     * 发单列表视图
     */
    public function sendList()
    {
        return view('front.order.send-list', [
            'games' => Game::getAll(),
        ]);
    }

    /**
     * 发单列表数据
     * @return array
     */
    public function sendListData()
    {
        $orders = GameLevelingOrder::getOrderByCondition(request()->all(), 1)->paginate(50);

        # 对获取数据进行处理后响应
        $ordersArr = $this->handleOrderList($orders);

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
    public function sendShow($tradeNo)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' => $tradeNo], 1)->firstOrFail();

        return view('front.order.show', [
            'order' => $order
        ]);
    }

    /**
     * @param $tradeNo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function takeShow($tradeNo)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' => $tradeNo], 2)->firstOrFail();

        return view('front.order.show', [
            'order' => $order
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('front.order.create')->with([
            'games' => Game::getAll(),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function store()
    {
       try {
           OrderService::init(request()->user()->parent_id)->create(
               request('game_id'),
               request('region_id'),
               request('server_id'),
               request('title'),
               request('game_account'),
               request('game_password'),
               request('game_role'),
               request('day'),
               request('hour'),
               request('game_leveling_type_id'),
               request('amount'),
               request('security_deposit'),
               request('efficiency_deposit'),
               request('explain'),
               request('requirement'),
               request('player_phone'),
               request('username', ''),
               request('user_phone', ''),
               request('user_qq', ''),
               request('take_order_password', ''),
               request('foreign_trade_no', '')
           );
           return response()->ajaxSuccess('下单成功');

       } catch (\Exception $exception) {
           return response()->ajaxFail();
       }

    }

    /**
     * 处理数据
     * @param $orders
     * @return array
     */
    private function handleOrderList($orders)
    {
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
                'explain',
                'requirement',
            ]);

            // 写入状态描述
            $ordersArr[$key]['status_describe'] = $item->getStatusDescribe();
            // 隐藏部分密码
            $ordersArr[$key]['game_password'] = str_replace(mb_substr($ordersArr[$key]['game_password'], -4, 4), '****', $ordersArr[$key]['game_password']);
            // 计算订单剩余代练时间
            $ordersArr[$key]['remaining_time'] = $item->getRemainingTime();
            // 计算订单获得金额 支付金额 手续费 利润 撤销发起人 仲裁发起人
            $ordersArr[$key]['income_amount'] = $item->getIncomeAmount();
            $ordersArr[$key]['expend_amount'] = $item->getExpendAmount();
            $ordersArr[$key]['poundage'] = $item->getPoundage();;
            $ordersArr[$key]['profit'] = $item->getProfit();
            $ordersArr[$key]['consult_initiator'] = $item->getConsultInitiator();
            $ordersArr[$key]['complain_initiator'] = $item->getComplainInitiator();
            $ordersArr[$key]['auto_complete_time'] = $item->getAutoCompleteTime();

            $ordersArr[$key]['consult_amount'] = $item->consult ?  $item->consult->amount : 0;
            $ordersArr[$key]['consult_deposit'] = $item->consult ?  $item->consult->security_deposit + $item->consult->efficiency_deposit : 0;
            $ordersArr[$key]['consult_reason'] = $item->consult ?  $item->consult->reason : '';
        }

        return $ordersArr;
    }
}
