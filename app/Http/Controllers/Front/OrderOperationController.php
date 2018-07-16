<?php

namespace App\Http\Controllers\Front;

use \Exception;
use App\Services\OrderServices;
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
        return response()->ajaxSuccess();
    }

    /**
     * 申请完成
     */
    public function applyComplete()
    {
        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->applyComplete();
        } catch (Exception $exception) {

        }
        DB::commit();
        return response()->ajaxSuccess();
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

        }
        DB::commit();
        return response()->ajaxSuccess();
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

        }
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

        }
        DB::commit();
        return response()->ajaxSuccess();
    }

    /**
     * 申请撤销
     */
    public function applyConsult()
    {
        $amount = request('amount');
        $securityDeposit = request('security_deposit');
        $efficiencyDeposit = request('remark');
        $remark = request('remark');

        DB::beginTransaction();
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->applyConsult($amount, $securityDeposit, $efficiencyDeposit, $remark);
        } catch (Exception $exception) {

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
            OrderServices::init(request()->user()->id, request('trade_no'))->applyComplain(request('remark'));
        } catch (Exception $exception) {

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

        }
        DB::commit();
        return response()->ajaxSuccess();
    }

}
