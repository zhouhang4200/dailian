<?php

namespace App\Http\Controllers\Back\Order;

use App\Services\OrderService;
use DB;
use Exception;
use App\Services\TmApiService;
use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use App\Models\GameLevelingOrderComplain;
use App\Models\GameLevelingOrderLog;
use App\Models\GameLevelingOrderMessage;
use App\Exceptions\Order\OrderTimeException;
use App\Exceptions\Order\OrderUserException;
use App\Exceptions\Order\OrderMoneyException;
use App\Exceptions\Order\OrderStatusException;
use App\Exceptions\Order\OrderPasswordException;
use App\Exceptions\Order\OrderAdminUserException;
use App\Exceptions\Order\OrderUnauthorizedException;

/**
 * 代练订单仲裁
 * Class OrderController
 * @package App\Http\Controllers\Back
 */
class GameLevelingOrderComplainController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.order.game-leveling-order-complain.index', [
            'complainOrders' => GameLevelingOrderComplain::with('order')->condition(request()->all())->paginate(),
            'statusCount' => GameLevelingOrderComplain::selectRaw('status, count(1) as count')
            ->groupBy('status')->pluck('count', 'status')->toArray(),
        ]);
    }

    /**
     * 订单详情
     * @param $tradeNO
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($tradeNO)
    {
        if (request()->ajax()) {
            return response()->ajaxSuccess('成功', [
                view()->make('back.order.game-leveling-order-complain.show-content', [
                    'order' => GameLevelingOrder::getOrderByCondition(['trade_no' =>  $tradeNO])->first(),
                ])->render()
            ]);
        }
        return view('back.order.game-leveling-order-complain.show', [
            'order' => GameLevelingOrder::getOrderByCondition(['trade_no' =>  $tradeNO])->first()
        ]);
    }

    /**
     * 发送仲裁留言
     * @return mixed
     * @throws Exception
     */
    public function sendMessage()
    {
        $order = GameLevelingOrder::getOrderByCondition(['trade_no' => request('trade_no')])->first();

        DB::beginTransaction();
        try {
            if (request('content')) {
                $message = GameLevelingOrderMessage::create([
                    'initiator' => 3,
                    'game_leveling_order_trade_no' => $order->trade_no,
                    'from_user_id' => 0,
                    'from_parent_user_id' => 0,
                    'from_username' => 0,
                    'to_user_id' => 0,
                    'to_username' => 0,
                    'content' => request('content'),
                    'type' => 1,
                ]);
                // 存储图片
                $image = base64ToImg(request('image'), 'complain');
                if ($image) {
                    $image['game_leveling_order_trade_no'] = $order->trade_no;
                    $message->image()->create($image);
                }
            }
            if (request('remark')) {
                $order->complain->remark = request('remark');
                $order->complain->save();
            }

        } catch (Exception $exception) {
            return response()->ajaxFail('发送失败-原因:' . $exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess('发送成功');
    }

    /**
     * 客服仲裁
     * @throws Exception
     */
    public function confirmArbitration()
    {
        DB::beginTransaction();
        try {
            // 拆分安全与效率保证金
            $depositResult = GameLevelingOrder::deuceDeposit(request('trade_no'), request('deposit'));

            $order = OrderService::init(0, request('trade_no'), request()->user('admin')->id)
                ->arbitration(request('amount'), $depositResult['security_deposit'], $depositResult['efficiency_deposit'], request('result'));

            TmApiService::arbitration(request('trade_no'), request('amount'), request('deposit'));

        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-confirmArbitration-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail('处理失败-原因: ' . $e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-confirmArbitration-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail('处理失败-原因: ' . $e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-confirmArbitration-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail('处理失败-原因: ' . $e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-confirmArbitration-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail('处理失败-原因: ' . $e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-confirmArbitration-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail('处理失败-原因: ' . $e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-confirmArbitration-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail('处理失败-原因: ' . $e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-confirmArbitration-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail('处理失败-原因: ' . $e->getMessage());
        }catch (Exception $e) {
            myLog('wanzi-operate-confirmArbitration-local-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail('处理失败-原因: 本地接口异常');
        }
        DB::commit();
        return response()->ajaxSuccess('处理成功');
    }

    /**
     * 订单日志
     * @param $tradeNO
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function log($tradeNO)
    {
        return view('back.order.log', [
            'logs' => GameLevelingOrderLog::where('game_leveling_order_trade_no', $tradeNO)->get(),
        ]);
    }
}
