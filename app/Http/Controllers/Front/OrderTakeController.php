<?php

namespace App\Http\Controllers\Front;

use \Exception;
use App\Models\Game;
use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use App\Models\GameLevelingOrderLog;
use App\Models\GameLevelingOrderMessage;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Front
 */
class OrderTakeController extends Controller
{
    /**
     * 待接单列表
     */
    public function index()
    {
        return view('front.order.take.index', [
            'games' => Game::getAll(),
        ]);
    }

    /**
     * 获取接单方数据列表
     * @return array
     */
    public function orderData()
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
     * @param $tradeNO
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($tradeNO)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' => $tradeNO], 2)->firstOrFail();

        return view('front.order.take.show', [
            'order' => $order
        ]);
    }

    /**
     * 订单操作记录
     * @param $tradeNO
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function operationLog($tradeNO)
    {
        $operationLog =  GameLevelingOrderLog::where('parent_user_id', request()->user()->parent_id)
            ->where('game_leveling_order_trade_no', $tradeNO)
            ->get();

        return  view('front.order.operation_log', [
            'operationLog' => $operationLog
        ]);
    }

    /**
     * 接单方查看仲裁信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function complainInfo($tradeNO)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' =>  $tradeNO], 2)->first();

        return response()->json(view()->make('front.order.take.complain_info', [
            'order' => $order,
        ])->render());
    }

    /**
     * 接单方发送仲裁留言
     * @return mixed
     */
    public function complainMessage()
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' => request('trade_no')], 2)->first();

        DB::beginTransaction();
        try {
            $message = GameLevelingOrderMessage::create([
                'initiator' => 2,
                'game_leveling_order_trade_no' => $order->trade_no,
                'from_user_id' => $order->take_user_id,
                'from_username' => $order->take_username,
                'to_user_id' => $order->user_id,
                'to_username' => $order->username,
                'content' => request('content'),
                'type' => 1,
            ]);
            // 存储图片
            $image = base64ToImg(request('image'), 'complain');
            if ($image) {
                $image['game_leveling_order_trade_no'] = $order->trade_no;
                $message->image()->create($image);
            }
        } catch (Exception $exception) {
            return response()->ajaxFail('发送失败');
        }
        DB::commit();
        return response()->ajaxSuccess('发送成功');
    }

    /**
     * @param $tradeNO
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function message($tradeNO)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' =>  $tradeNO], 2)->with([
            'message' => function($query){
                return $query->where('type', 2);
            }
        ])->first();

        return response()->json(view()->make('front.order.take.message', [
            'order' => $order,
        ])->render());
    }

    /**
     * 发送订单留言
     * @param $tradeNO
     *
     * @return mixed
     */
    public function sendMessage($tradeNO)
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' =>  $tradeNO], 2)->with([
            'message' => function($query){
                return $query->where('type', 2);
            }
        ])->firstOrFail();

        $order->message()->create([
            'initiator' => 2,
            'from_user_id' => $order->take_parent_user_id,
            'from_username' => $order->take_parent_username,
            'to_user_id' => $order->parent_user_id,
            'to_username' => $order->username,
            'content' => request('content'),
            'type' => 2,
        ]);
        return response()->ajaxSuccess();
    }
}
