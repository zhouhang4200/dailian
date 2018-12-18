<?php

namespace App\Http\Controllers\Front;

use Exception;
use App\Models\GameLevelingOrderAnomaly;
use App\Exceptions\OrderException;
use App\Exceptions\UserAssetException;
use App\Models\GameLevelingOrder;
use App\Models\GameLevelingOrderLog;
use App\Models\GameLevelingOrderMessage;
use App\Services\TmApiService;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 订单操作
 * Class OrderOperationController
 *
 * @package App\Http\Controllers\Front
 */
class OrderOperationController extends Controller
{
    private $taobaoOrder = false;

    /**
     * 发单用户为 1 的订单则需要调用发单平台接口
     * OrderOperationController constructor.
     */
    public function __construct()
    {
        $sendUserId = GameLevelingOrder::where('trade_no', request('trade_no'))->value('parent_user_id');

        if (in_array($sendUserId, [1])) {
            $this->taobaoOrder = true;
        }
    }

    /**
     * 接单
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function take()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))
                ->take(clientRSADecrypt(request('pay_password')), clientRSADecrypt(request('take_password')));

            if ($this->taobaoOrder) {
                TmApiService::take($order);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage(), [], $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess('接单成功');
    }

    /**
     * 撤单
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))
                ->delete();

            if ($this->taobaoOrder) {
                TmApiService::delete($order);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage(), [], $e->getCode());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess('撤单成功');
    }

    /**
     * 申请完成
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function applyComplete()
    {
        // 获取图片信息组成仲裁需要的数组
        $images[] = base64ToImg(request('image_1'), 'complete');
        $images[] = base64ToImg(request('image_2'), 'complete');
        $images[] = base64ToImg(request('image_3'), 'complete');

        if (count($images) == 0) {
            return response()->ajaxFail('提交验收至少需要一张图片');
        }

        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->applyComplete(array_filter($images), request('remark'));

            if ($this->taobaoOrder) {
                TmApiService::applyComplete($order->trade_no);
            }

        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess('提交验收成功');
    }

    /**
     * 取消完成
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelComplete()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->cancelComplete();

            if ($this->taobaoOrder) {
                TmApiService::cancelComplete($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess('取消验收成功');
    }

    /**
     * 完成订单
     * @return mixed
     * @throws Exception
     */
    public function complete()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->complete();

            if ($this->taobaoOrder) {
                TmApiService::complete($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 上架
     * @return mixed
     * @throws Exception
     */
    public function onSale()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->onSale();

            if ($this->taobaoOrder) {
                TmApiService::onSale($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 下架
     * @return mixed
     * @throws Exception
     */
    public function offSale()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->offSale();

            if ($this->taobaoOrder) {
                TmApiService::offSale($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 锁定
     * @return mixed
     * @throws Exception
     */
    public function lock()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->lock();

            if ($this->taobaoOrder) {
                TmApiService::lock($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 取消锁定
     * @return mixed
     * @throws Exception
     */
    public function cancelLock()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->cancelLock();

            if ($this->taobaoOrder) {
                TmApiService::cancelLock($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 异常
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function anomaly()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->anomaly(request('reason'));

            if ($this->taobaoOrder) {
                TmApiService::anomaly($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 取消异常
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelAnomaly()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->cancelAnomaly();

            if ($this->taobaoOrder) {
                TmApiService::cancelAnomaly($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 申请撤销
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function applyConsult()
    {
        $tradeNO = request('trade_no');
        $amount = request('amount');
        $deposit = request('deposit');
        $reason = request('reason');

        DB::beginTransaction();

        // 拆分安全与效率保证金
        $depositResult = GameLevelingOrder::deuceDeposit($tradeNO, $deposit);

        try {
            $order = OrderService::init(request()->user()->id, $tradeNO)
                ->applyConsult($amount, $depositResult['security_deposit'], $depositResult['efficiency_deposit'], $reason);

            if ($this->taobaoOrder) {
                TmApiService::applyConsult($order->trade_no, $amount, $deposit, $reason);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 取消撤销
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelConsult()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->cancelConsult();

            if ($this->taobaoOrder) {
                TmApiService::cancelConsult($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 同意撤销
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function agreeConsult()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->agreeConsult();

            if ($this->taobaoOrder) {
                TmApiService::agreeConsult($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }


    /**
     * 不同意撤销
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function rejectConsult()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->rejectConsult();

            if ($this->taobaoOrder) {
                TmApiService::rejectConsult($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 申请仲裁
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function applyComplain()
    {
        DB::beginTransaction();
        try {
            // 获取图片信息组成仲裁需要的数组
            $images[] = base64ToImg(request('image_1'), 'complain');
            $images[] = base64ToImg(request('image_2'), 'complain');
            $images[] = base64ToImg(request('image_3'), 'complain');

            $order = OrderService::init(request()->user()->id, request('trade_no'))->applyComplain(request('reason'), array_filter($images));

            if ($this->taobaoOrder) {
                TmApiService::applyComplain($order->trade_no, request('reason'));
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();

    }

    /**
     * 取消仲裁
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelComplain()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->cancelComplain();

            if ($this->taobaoOrder) {
                TmApiService::cancelComplain($order->trade_no);
            }
        } catch (OrderException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetException $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->ajaxFail($e->getMessage());
        }
        DB::commit();

        return response()->ajaxSuccess();
    }

    /**
     * 查看验收图片
     * @param $tradeNO
     * @return mixed
     * @throws Exception
     */
    public function applyCompleteImage($tradeNO)
    {
        try {
            $images = OrderService::init(request()->user()->id, $tradeNO)->applyCompleteImage();
        } catch (OrderException $exception) {
            return response()->ajaxFail($exception->getMessage());
        }

        return response()->ajaxSuccess('获取成功', view()->make('front.order-operation.apply-complete-image', [
            'image' => $images,
        ])->render());
    }

    /**
     * 订单操作记录
     * @param $tradeNO
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function log($tradeNO)
    {
        $operationLog = GameLevelingOrderLog::where('parent_user_id', request()->user()->parent_id)
            ->where('game_leveling_order_trade_no', $tradeNO)
            ->get();

        return view('front.order-operation.log', [
            'operationLog' => $operationLog
        ]);
    }

    /**
     * 查看仲裁信息
     * @param $tradeNO
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function complainInfo($tradeNO)
    {
        $complainInfo = null;
        try {
            $complainInfo = OrderService::init(request()->user()->id, $tradeNO)->getComplainInfo();
        } catch (OrderException $exception) {

        }
        return response()->json(view()->make('front.order-operation.complain-info', [
            'order' => $complainInfo,
        ])->render());
    }

    /**
     * 发送仲裁留言
     * @return mixed
     * @throws Exception
     */
    public function sendComplainMessage()
    {
        try {
            // 存储图片
            $image = base64ToImg(request('image'), 'complain');

            OrderService::init(request()->user()->id, request('trade_no'))->sendComplainMessage($image, request('content'));
        } catch (Exception $exception) {
            return response()->ajaxFail('发送失败');
        }
        DB::commit();
        return response()->ajaxSuccess('发送成功');
    }

    /**
     * @param $tradeNO
     * @return \Illuminate\Http\JsonResponse
     * @throws OrderException
     */
    public function message($tradeNO)
    {
        return response()->json(view()->make('front.order-operation.message', [
            'messages' => OrderService::init(request()->user()->id, $tradeNO)->getMessage(),
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
        try {
            OrderService::init(request()->user()->id, $tradeNO)->sendMessage(request('content'));
        } catch (Exception $exception) {
            return response()->ajaxFail('发送失败');
        }
        DB::commit();
        return response()->ajaxSuccess('发送成功');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function messageList()
    {
        return view('front.order-operation.message-list', [
            'messages' => GameLevelingOrderMessage::where('to_user_id', request()->user()->parent_id)
                ->orderBy('id', 'desc')
                ->where('status', 1)->get(),
        ]);
    }

    /**
     * 删除留言
     * @return mixed
     */
    public function deleteMessage()
    {
        GameLevelingOrderMessage::where('to_user_id', request()->user()->parent_id)
            ->where('id', request('id'))
            ->update(['status' => 2]);
        return response()->ajaxSuccess('删除成功');
    }

    /**
     * 删除所有留言
     * @return mixed
     */
    public function deleteAllMessage()
    {
        GameLevelingOrderMessage::where('to_user_id', request()->user()->parent_id)
            ->update(['status' => 2]);
        return response()->ajaxSuccess('删除成功');
    }
}
