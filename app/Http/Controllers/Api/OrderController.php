<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use GuzzleHttp\Client;
use App\Models\Game;
use App\Models\Server;
use App\Models\Region;
use Illuminate\Http\UploadedFile;
use App\Models\GameLevelingType;
use App\Services\OrderService;
use App\Models\GameLevelingOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\Order\OrderTimeException;
use App\Exceptions\Order\OrderUserException;
use App\Exceptions\Order\OrderMoneyException;
use App\Exceptions\Order\OrderStatusException;
use App\Exceptions\Order\OrderPasswordException;
use App\Exceptions\Order\OrderAdminUserException;
use App\Exceptions\Order\OrderUnauthorizedException;

class OrderController extends Controller
{
    // 发单器在丸子这边的发单账号ID
    private static $creatorUserId = 1;
    // 发单器给的key
    private static $key = '335ss6s8m8e4f5a8e2e2ls5';
    // 发单器给的IV
    private static $iv = '1234567891111152';
    // 发单器的回调地址
    private static $callbackUrl = 'www.test.com/api/partner/order/callback';
    // 发单器那边的app_id, app_secret
    private static $appId = 'T8WsMDT4mJ5DxKJkf4fWVP5XYU00McJxxyAeoX4aPIy6jrWN70bmQltXfwof';
    private static $appSecret = 'XlDzhGb9EeiJW2r6os1CVC6bKLrikFDHgH5mVLGdVRMNyYhY7Q4QvFIL2SBx';
    // 允许上传图片类型
    private static $extensions = ['png', 'jpg', 'jpeg', 'gif'];

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

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->onSale();
        } catch (OrderTimeException $e) {
            myLog('operate-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
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

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->offSale();
        } catch (OrderTimeException $e) {
            myLog('operate-offSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-offSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-offSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-offSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-offSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-offSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-offSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-offSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
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
            $order = GameLevelingOrder::where('trade_no', $orderNo)->first();

            if ($order->status != 13) {
                $orderService = OrderService::init($userId, $orderNo);
                $orderService->delete();
            }
        } catch (OrderTimeException $e) {
            myLog('operate-delete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-delete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-delete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-delete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-delete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-delete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-delete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-delete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
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

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->complete();
        } catch (OrderTimeException $e) {
            myLog('operate-complete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-complete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-complete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-complete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-complete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-complete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-complete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-complete-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
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

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->lock();
        } catch (OrderTimeException $e) {
            myLog('operate-lock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-lock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-lock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-lock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-lock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-lock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-lock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-lock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
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

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->cancelLock();
        } catch (OrderTimeException $e) {
            myLog('operate-cancelLock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-cancelLock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-cancelLock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-cancelLock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-cancelLock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-cancelLock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-cancelLock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelLock-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
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
            $amount = $request->amount; // 发单方愿意支付的代练费
            $doubleDeposit = $request->double_deposit; // 需要接单赔偿的双金
            $arr = GameLevelingOrder::deuceDeposit($orderNo, $doubleDeposit);
            $securityDeposit = $arr['security_deposit']; // 需要接单赔偿的安全金
            $efficiencyDeposit = $arr['efficiency_deposit']; // 需要接单赔偿的效率金
            $reason = $request->reason; // 申请协商原因

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->applyConsult($amount, $securityDeposit, $efficiencyDeposit, $reason);
        } catch (OrderTimeException $e) {
            myLog('operate-applyConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-applyConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-applyConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-applyConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-applyConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-applyConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-applyConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-applyConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
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
            myLog('no', ['no' => $orderNo]);
            $orderService = OrderService::init($userId, $orderNo);
            $orderService->cancelConsult();
        } catch (OrderTimeException $e) {
            myLog('operate-cancelConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-cancelConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-cancelConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-cancelConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-cancelConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-cancelConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-cancelConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
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

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->agreeConsult();
        } catch (OrderTimeException $e) {
            myLog('operate-agreeConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-agreeConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-agreeConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-agreeConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-agreeConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-agreeConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-agreeConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-agreeConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     *  不同意协商
     * @param Request $request
     * @return mixed
     */
    public function rejectConsult(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->refuseConsult();
        } catch (OrderTimeException $e) {
            myLog('operate-refuseConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-refuseConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-refuseConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-refuseConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-refuseConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-refuseConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-refuseConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-refuseConsult-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
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
            $reason = $request->reason;
            $pic1 = $request->pic1;
            $pic2 = $request->pic2;
            $pic3 = $request->pic3;
            $path = public_path("/resources/complain/".date('Ymd')."/");
            if (isset($pic1) && ! empty($pic1)) {
                $imagePath1 = static::uploadImage($pic1, $path);
                $image['pic1']['mime_type'] = 'image/'.explode('.', $imagePath1)[1];
                $image['pic1']['name'] = explode('/', $imagePath1)[count(explode('/', $imagePath1))-1];
                $image['pic1']['path'] = $imagePath1;
            }

            if (isset($pic2) && ! empty($pic2)) {
                $imagePath2 = static::uploadImage($pic2, $path);
                $image['pic2']['mime_type'] = 'image/'.explode('.', $imagePath2)[1];
                $image['pic2']['name'] = explode('/', $imagePath2)[count(explode('/', $imagePath2))-1];
                $image['pic2']['path'] = $imagePath2;
            }

            if (isset($pic3) && ! empty($pic3)) {
                $imagePath3 = static::uploadImage($pic3, $path);
                $image['pic3']['mime_type'] = 'image/'.explode('.', $imagePath3)[1];
                $image['pic3']['name'] = explode('/', $imagePath3)[count(explode('/', $imagePath3))-1];
                $image['pic3']['path'] = $imagePath3;
            }

//            myLog('image', ['image' => $image, 'pic1' => $pic1, 'pic2' => $pic2, 'pic3' => $pic3]);
//            myLog('image', ['image' => $image, 'reason' => $reason]);
            $orderService = OrderService::init($userId, $orderNo);
            $orderService->applyComplain($reason, $image);
        } catch (OrderTimeException $e) {
            myLog('operate-applyComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-applyComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-applyComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-applyComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-applyComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-applyComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-applyComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-applyComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     * 接收远程传过来的图片
     * @param UploadedFile $file
     * @param $path
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public static function uploadImage(UploadedFile $file, $path)
    {
        // 获取可传输的图片类型
        $extension = $file->getClientOriginalExtension();

        if ($extension && ! in_array(strtolower($extension), static::$extensions)) {
            return response()->apiFail('图片类型不合法');
        }
        // 判断上传是否为空
        if (! $file->isValid()) {
            return response()->apiFail('图片为空');
        }
        // 不存在存储路径的时候指定路径
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        // 图片随机命名
        $randNum = rand(1, 100000000) . rand(1, 100000000);
        $fileName = time().substr($randNum, 0, 6).'.'.$extension;
        // 保存图片
        $path = $file->move($path, $fileName);
        $path = strstr($path, '/resources');
        // 返回图片路径
        return str_replace('\\', '/', $path);
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

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->cancelComplain();
        } catch (OrderTimeException $e) {
            myLog('operate-cancelComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-cancelComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-cancelComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-cancelComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-cancelComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-cancelComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-cancelComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelComplain-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     * 接收发单参数，创建订单
     * @param Request $request
     * @return bool
     */
    public function placeOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->data;
            if (! isset($data) || ! $data) {
                throw new Exception('接收信息为空');
            }
            $decriptData = openssl_decrypt($data, 'aes-128-cbc', static::$key, false, static::$iv);
            $data = json_decode($decriptData, true);
            $orderService = OrderService::init(static::$creatorUserId);
            $game = Game::where('name', $data['game_name'])->first();
            $region = Region::where('name', $data['game_region'])->where('game_id', $game->id)->first();
            $server = Server::where('name', $data['game_serve'])->where('region_id', $region->id)->first();
            $gameLevelingType = GameLevelingType::where('game_id', $game->id)->first();

            $order = $orderService->create(
                $game->id,
                $region->id,
                $server->id,
                $data['game_leveling_title'],
                $data['game_account'],
                $data['game_password'],
                $data['game_role'],
                $data['game_leveling_day'],
                $data['game_leveling_hour'],
                $gameLevelingType->id,
                $data['game_leveling_price'],
                $data['game_leveling_security_deposit'],
                $data['game_leveling_efficiency_deposit'],
                $data['game_leveling_instructions'],
                $data['game_leveling_requirements'],
                $data['businessman_phone'],
                $data['businessman_qq'],
                $data['order_password'],
                $data['order_no']
            );
            // 回调
            if ($order) {
                $options = [
                    'no' => $data['order_no'],
                    'order_no' => $order->trade_no,
                    'app_id' => static::$appId,
                    'timestamp' => time()
                ];
                // 合成发单器的签名
                $sign = $this->generateSign($options);
                $options['sign'] = $sign;

                $client = new Client();
                $response = $client->request('POST', static::$callbackUrl, [
                    'form_params' => $options,
                    'body' => 'x-www-form-urlencoded',
                ]);
                $result = $response->getBody()->getContents();
                $result = json_decode($result, true);

                if (! isset($result['code']) || $result['code'] != 1) {
                    throw new OrderServiceException('调用发单器回调接口失败');
                }
            } else {
                throw new Exception('丸子下单失败');
            }
        } catch (OrderTimeException $e) {
            myLog('place-order-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('place-order-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('place-order-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('place-order-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('place-order-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('place-order-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('place-order-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            DB::rollback();
            myLog('place-order-local-error', ['data' => $data, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        }
        DB::commit();
        myLog('place-order-success', ['发单器结果' => $result, '从发单器获取的参数' => $data, '发送给发单器的参数' => $options]);
        return response()->apiSuccess('下单成功', ['order_no' => $order->trade_no]);
    }

    /**
     * 详情
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function detail(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderService::init($userId, $orderNo);
            $order = $orderService->detail();
            $data = [
                'order_no' => $order->trade_no,
                'amount' => $order->amount,
                'game_name' => $order->game_name,
                'region_name' => $order->region_name,
                'server_name' => $order->server_name,
                'title' => $order->title,
            ];
        } catch (OrderTimeException $e) {
            myLog('operate-detail-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUserException $e) {
            myLog('operate-detail-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderMoneyException $e) {
            myLog('operate-detail-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderStatusException $e) {
            myLog('operate-detail-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderPasswordException $e) {
            myLog('operate-detail-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderAdminUserException $e) {
            myLog('operate-detail-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (OrderUnauthorizedException $e) {
            myLog('operate-detail-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-detail-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess('成功', $data);
    }

    /**
     * 合成发单器的sign
     * @param $options
     * @return string
     */
    public function generateSign($options)
    {
        ksort($options);
        $str = '';
        foreach ($options as $key => $value) {
            $str .= $key . '=' . $value . '&';
        }
        return md5(rtrim($str,  '&') . static::$appSecret);
    }
}
