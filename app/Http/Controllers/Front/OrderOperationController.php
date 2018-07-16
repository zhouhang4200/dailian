<?php

namespace App\Http\Controllers\Front;

use App\Services\OrderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        try {
            OrderServices::init(request()->user()->id, request('trade_no'))->take();
        } catch (\Exception $exception) {
            return response()->ajaxFail();
        }

    }

    /**
     * 申请完成
     */
    public function applyComplete()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 取消完成
     */
    public function cancelComplete()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 完成订单
     */
    public function complete()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 上架
     */
    public function onSale()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 下架
     */
    public function offSale()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /***
     * 锁定
     */
    public function lock()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 取消锁定
     */
    public function cancelLock()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 异常
     */
    public function anomaly()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 取消异常
     */
    public function cancelAnomaly()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 申请撤销
     */
    public function applyConsult()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 取消撤销
     */
    public function cancelConsult()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 申请仲裁
     */
    public function applyComplain()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

    /**
     * 取消仲裁
     */
    public function cancelComplain()
    {
        OrderServices::init(request()->user()->id, request('trade_no'))->take();
    }

}
