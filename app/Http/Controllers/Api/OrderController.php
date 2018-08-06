<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Services\OrderServices;
use App\Models\GameLevelingOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     *  上架
     * @param Request $request
     * @return mixed
     */
    public function onSale(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->onSale();
        } catch (DailianException $e) {
            myLog('operate-onSale', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-onSale', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  下架
     * @param Request $request
     * @return mixed
     */
    public function offSale(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->offSale();
        } catch (DailianException $e) {
            myLog('operate-offSale', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-offSale', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  撤单
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            // 已经撤单的返回成功
            $order = GameLevelingOrder::where('no', $orderNo)->first();
            if ($order->status != 14) {
                $orderService = OrderServices::init($userId, $orderNo);
                $orderService->delete();
            }
        } catch (DailianException $e) {
            myLog('operate-delete', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-delete', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  申请验收
     * @param Request $request
     * @return mixed
     */
    public function applyComplete(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;
            $images = [];

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->applyComplete($images);
        } catch (DailianException $e) {
            myLog('operate-applyComplete', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-applyComplete', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  取消验收
     * @param Request $request
     * @return mixed
     */
    public function cancelComplete(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->cancelComplete();
        } catch (DailianException $e) {
            myLog('operate-cancelComplete', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelComplete', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  完成
     * @param Request $request
     * @return mixed
     */
    public function complete(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->complete();
        } catch (DailianException $e) {
            myLog('operate-complete', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-complete', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  锁定
     * @param Request $request
     * @return mixed
     */
    public function lock(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->lock();
        } catch (DailianException $e) {
            myLog('operate-lock', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-lock', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  取消锁定
     * @param Request $request
     * @return mixed
     */
    public function cancelLock(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->cancelLock();
        } catch (DailianException $e) {
            myLog('operate-cancelLock', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelLock', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  异常
     * @param Request $request
     * @return mixed
     */
    public function anomaly(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->anomaly();
        } catch (DailianException $e) {
            myLog('operate-anomaly', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-anomaly', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  取消异常
     * @param Request $request
     * @return mixed
     */
    public function cancelAnomaly(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->cancelAnomaly();
        } catch (DailianException $e) {
            myLog('operate-cancelAnomaly', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelAnomaly', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  申请协商
     * @param Request $request
     * @return mixed
     */
    public function applyConsult(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->applyConsult();
        } catch (DailianException $e) {
            myLog('operate-applyConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-applyConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  取消协商
     * @param Request $request
     * @return mixed
     */
    public function cancelConsult(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->cancelConsult();
        } catch (DailianException $e) {
            myLog('operate-cancelConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  同意协商
     * @param Request $request
     * @return mixed
     */
    public function agreeConsult(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->agreeConsult();
        } catch (DailianException $e) {
            myLog('operate-agreeConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-agreeConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  申请仲裁
     * @param Request $request
     * @return mixed
     */
    public function applyComplain(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->applyComplain();
        } catch (DailianException $e) {
            myLog('operate-applyComplain', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-applyComplain', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }

    /**
     *  取消仲裁
     * @param Request $request
     * @return mixed
     */
    public function cancelComplain(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->cancelComplain();
        } catch (DailianException $e) {
            myLog('operate-cancelComplain', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelComplain', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess('成功');
    }
}
