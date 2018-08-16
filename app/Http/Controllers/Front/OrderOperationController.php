<?php

namespace App\Http\Controllers\Front;

use App\Exceptions\Order\OrderComplainException;
use App\Exceptions\Order\OrderImageException;
use App\Models\GameLevelingOrderLog;
use Exception;
use App\Exceptions\UserAsset\UserAssetBalanceException;
use App\Exceptions\Order\OrderTimeException;
use App\Exceptions\Order\OrderUserException;
use App\Exceptions\Order\OrderMoneyException;
use App\Exceptions\Order\OrderStatusException;
use App\Exceptions\Order\OrderPasswordException;
use App\Exceptions\Order\OrderAdminUserException;
use App\Exceptions\Order\OrderUnauthorizedException;
use App\Services\OrderService;
use App\Models\GameLevelingOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\TmApiService;

/**
 * 订单操作
 * Class OrderOperationController
 *
 * @package App\Http\Controllers\Front
 */
class OrderOperationController extends Controller
{
    /**
     * 接单
     * @return mixed
     * @throws Exception
     */
    public function take()
    {
        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))
                ->take(clientRSADecrypt(request('pay_password')), clientRSADecrypt(request('take_password')));
            TmApiService::take($order);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (UserAssetBalanceException $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->json(['status' => 5, 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            myLog('wanzi-operate-take-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->json(['status' => 5, 'message' => '未知错误' . $e->getMessage()]);
        }
        DB::commit();
        return response()->ajaxSuccess('接单成功');
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
        $images[] = base64ToImg(request('image_1'),  'complete');
        $images[] = base64ToImg(request('image_2'),  'complete');
        $images[] = base64ToImg(request('image_3'),  'complete');

        if (count($images) == 0)  {
            return response()->ajaxFail('提交验收至少需要一张图片');
        }

        DB::beginTransaction();
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->applyComplete(array_filter($images));
            TmApiService::applyComplete($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-applyComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-applyComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-applyComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-applyComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-applyComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-applyComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-applyComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-applyComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::cancelComplete($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-cancelComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-cancelComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-cancelComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-cancelComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-cancelComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-cancelComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-cancelComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-cancelComplete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::complete($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-complete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
           myLog('wanzi-operate-complete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
           myLog('wanzi-operate-complete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
           myLog('wanzi-operate-complete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
           myLog('wanzi-operate-complete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
           myLog('wanzi-operate-complete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
           myLog('wanzi-operate-complete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-complete-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::onSale($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-onSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-onSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-onSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-onSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-onSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-onSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-onSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-onSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::offSale($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-offSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-offSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-offSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-offSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-offSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-offSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-offSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-offSale-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::lock($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-lock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-lock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-lock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-lock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-lock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-lock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-lock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-lock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::cancelLock($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-cancelLock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-cancelLock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-cancelLock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-cancelLock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-cancelLock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-cancelLock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-cancelLock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-cancelLock-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            $order = OrderService::init(request()->user()->id, request('trade_no'))->anomaly();
            TmApiService::anomaly($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-anomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-anomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-anomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-anomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-anomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-anomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-anomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-anomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
        try {
            $order = OrderService::init(request()->user()->id, request('trade_no'))->cancelAnomaly();
            TmApiService::cancelAnomaly($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-cancelAnomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-cancelAnomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-cancelAnomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-cancelAnomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-cancelAnomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-cancelAnomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-cancelAnomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-cancelAnomaly-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::applyConsult($order->trade_no, $amount, $deposit, $reason);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-applyConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-applyConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-applyConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-applyConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-applyConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-applyConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-applyConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-applyConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::cancelConsult($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-cancelConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-cancelConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-cancelConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-cancelConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-cancelConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-cancelConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-cancelConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-cancelConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::agreeConsult($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-agreeConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-agreeConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-agreeConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-agreeConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-agreeConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-agreeConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-agreeConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-agreeConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::rejectConsult($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-rejectConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-rejectConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-rejectConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-rejectConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-rejectConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-rejectConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-rejectConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-rejectConsult-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            $images[] = base64ToImg(request('image_1'),  'complain');
            $images[] = base64ToImg(request('image_2'),  'complain');
            $images[] = base64ToImg(request('image_3'),  'complain');

            $order = OrderService::init(request()->user()->id, request('trade_no'))->applyComplain(request('reason'), array_filter($images));
            TmApiService::applyComplain($order->trade_no, request('reason'));
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-applyComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('wanzi-operate-applyComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('wanzi-operate-applyComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('wanzi-operate-applyComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('wanzi-operate-applyComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('wanzi-operate-applyComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('wanzi-operate-applyComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-applyComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
            TmApiService::cancelComplain($order->trade_no);
        } catch (OrderTimeException $e) {
            myLog('wanzi-operate-cancelComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUserException $e) {
             myLog('wanzi-operate-cancelComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderMoneyException $e) {
             myLog('wanzi-operate-cancelComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderStatusException $e) {
             myLog('wanzi-operate-cancelComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderPasswordException $e) {
             myLog('wanzi-operate-cancelComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
             myLog('wanzi-operate-cancelComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
             myLog('wanzi-operate-cancelComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
            return response()->ajaxFail($e->getMessage());
        } catch (Exception $e) {
            myLog('wanzi-operate-cancelComplain-error', ['no' => $order->trade_no ?? '', 'message' => $e->getMessage()]);
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
        } catch (OrderImageException $imageException) {
            return response()->ajaxFail('暂时没有图片');
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
        $operationLog =  GameLevelingOrderLog::where('parent_user_id', request()->user()->parent_id)
            ->where('game_leveling_order_trade_no', $tradeNO)
            ->get();

        return  view('front.order-operation.log', [
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
        } catch (OrderComplainException $exception) {

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
            OrderService::init(request()->user()->id, request('trade_no'))
                ->sendComplainMessage(request('image'), request('content'));
        } catch (Exception $exception) {
            return response()->ajaxFail('发送失败');
        }
        DB::commit();
        return response()->ajaxSuccess('发送成功');
    }

    /**
     * @param $tradeNO
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Order\OrderUnauthorizedException
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
}
