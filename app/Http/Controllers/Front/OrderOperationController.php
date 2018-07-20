<?php

namespace App\Http\Controllers\Front;

use \Exception;
use App\Services\OrderServices;
use App\Models\GameLevelingOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
     */
    public function take()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->take();
        } catch (Exception $exception) {
            return response()->ajaxFail();
        }
        DB::commit();
        return response()->ajaxSuccess('接单成功');
    }

    /**
     * 申请完成
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
            OrderServices::init(request()->user()->id, request('trade_no'))->applyComplete(array_filter($images));
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess('提交验收成功');
    }

    /**
     * 取消完成
     */
    public function cancelComplete()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->cancelComplete();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess('取消验收成功');
    }

    /**
     * 完成订单
     */
    public function complete()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->complete();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 上架
     */
    public function onSale()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->onSale();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 下架
     */
    public function offSale()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->offSale();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /***
     * 锁定
     */
    public function lock()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->lock();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 取消锁定
     */
    public function cancelLock()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->cancelLock();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 异常
     */
    public function anomaly()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->anomaly();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 取消异常
     */
    public function cancelAnomaly()
    {
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->cancelAnomaly();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 申请撤销
     */
    public function applyConsult()
    {
        $tradeNO = request('trade_no');
        $amount = request('amount');
        $deposit = request('deposit');
        $remark = request('remark');
        $securityDeposit = 0;
        $efficiencyDeposit = 0;

        DB::beginTransaction();

        // 拆分安全与效率保证金
        if ($deposit > 0) {
            $order = GameLevelingOrder::getOrderByCondition(['trade_no' => $tradeNO])->first();
            $securityDeposit = $order->security_deposit;
            $efficiencyDeposit = bcsub($deposit, $order->efficiency_deposit);
        }
        try {
            OrderServices::init(request()->user()->id, $tradeNO)->applyConsult($amount, $securityDeposit, $efficiencyDeposit, $remark);
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 取消撤销
     */
    public function cancelConsult()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->cancelConsult();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 申请仲裁
     */
    public function applyComplain()
    {
        DB::beginTransaction();
        try {
            // 获取图片信息组成仲裁需要的数组
            $images[] = base64ToImg(request('image_1'),  'complain');
            $images[] = base64ToImg(request('image_2'),  'complain');
            $images[] = base64ToImg(request('image_3'),  'complain');

            OrderServices::init(request()->user()->id, request('trade_no'))->applyComplain(request('reason'), array_filter($images));
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();

    }

    /**
     * 取消仲裁
     */
    public function cancelComplain()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->cancelComplain();
        } catch (Exception $exception) {
            return response()->ajaxFail($exception->getMessage());
        }
        DB::commit();
        return response()->ajaxSuccess();
    }

}
