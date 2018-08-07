<?php

namespace App\Http\Controllers\Api;

use DB;
use GuzzleHttp\Client;
use App\Models\Game;
use App\Models\Server;
use App\Models\Region;
use App\Models\GameLevelingType;
use Exception;
use App\Services\OrderServices;
use App\Models\GameLevelingOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // 发单器给的key
    private static $key = '335ss6s8m8e4f5a8e2e2ls5';
    // 发单器给的IV
    private static $iv = '1234567891111152';
    // 发单器的回调地址
    private static $callbackUrl = 'www.test.com/api/partner/order/callback';
    // 发单器那边的app_id, app_secret
    private static $appId = 'T8WsMDT4mJ5DxKJkf4fWVP5XYU00McJxxyAeoX4aPIy6jrWN70bmQltXfwof';
    private static $appSecret = 'XlDzhGb9EeiJW2r6os1CVC6bKLrikFDHgH5mVLGdVRMNyYhY7Q4QvFIL2SBx';
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
            $order = GameLevelingOrder::where('no', $orderNo)->first();

            if ($order->status != 13) {
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
        return response()->apiSuccess();
    }

//    /**
//     *  申请验收
//     * @param Request $request
//     * @return mixed
//     */
//    public function applyComplete(Request $request)
//    {
//        try {
//            $orderNo = $request->order_no;
//            $userId = $request->user->id;
//            $images = [];
//
//            $orderService = OrderServices::init($userId, $orderNo);
//            $orderService->applyComplete($images);
//        } catch (DailianException $e) {
//            myLog('operate-applyComplete', ['no' => $orderNo, 'message' => $e->getMessage()]);
//            return response()->apiFail($e->getMessage());
//        } catch (Exception $e) {
//            myLog('operate-local-applyComplete', ['no' => $orderNo, 'message' => $e->getMessage()]);
//            return response()->apiFail('接口异常');
//        }
//        return response()->apiSuccess();
//    }
//
//    /**
//     *  取消验收
//     * @param Request $request
//     * @return mixed
//     */
//    public function cancelComplete(Request $request)
//    {
//        try {
//            $orderNo = $request->order_no;
//            $userId = $request->user->id;
//
//            $orderService = OrderServices::init($userId, $orderNo);
//            $orderService->cancelComplete();
//        } catch (DailianException $e) {
//            myLog('operate-cancelComplete', ['no' => $orderNo, 'message' => $e->getMessage()]);
//            return response()->apiFail($e->getMessage());
//        } catch (Exception $e) {
//            myLog('operate-local-cancelComplete', ['no' => $orderNo, 'message' => $e->getMessage()]);
//            return response()->apiFail('接口异常');
//        }
//        return response()->apiSuccess();
//    }

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

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->lock();
        } catch (DailianException $e) {
            myLog('operate-lock', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-lock', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
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

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->cancelLock();
        } catch (DailianException $e) {
            myLog('operate-cancelLock', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelLock', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess();
    }

//    /**
//     *  异常
//     * @param Request $request
//     * @return mixed
//     */
//    public function anomaly(Request $request)
//    {
//        try {
//            $orderNo = $request->order_no;
//            $userId = $request->user->id;
//
//            $orderService = OrderServices::init($userId, $orderNo);
//            $orderService->anomaly();
//        } catch (DailianException $e) {
//            myLog('operate-anomaly', ['no' => $orderNo, 'message' => $e->getMessage()]);
//            return response()->apiFail($e->getMessage());
//        } catch (Exception $e) {
//            myLog('operate-local-anomaly', ['no' => $orderNo, 'message' => $e->getMessage()]);
//            return response()->apiFail('接口异常');
//        }
//        return response()->apiSuccess();
//    }
//
//    /**
//     *  取消异常
//     * @param Request $request
//     * @return mixed
//     */
//    public function cancelAnomaly(Request $request)
//    {
//        try {
//            $orderNo = $request->order_no;
//            $userId = $request->user->id;
//
//            $orderService = OrderServices::init($userId, $orderNo);
//            $orderService->cancelAnomaly();
//        } catch (DailianException $e) {
//            myLog('operate-cancelAnomaly', ['no' => $orderNo, 'message' => $e->getMessage()]);
//            return response()->apiFail($e->getMessage());
//        } catch (Exception $e) {
//            myLog('operate-local-cancelAnomaly', ['no' => $orderNo, 'message' => $e->getMessage()]);
//            return response()->apiFail('接口异常');
//        }
//        return response()->apiSuccess();
//    }

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
            $securityDeposit = $request->security_deposit; // 需要接单赔偿的安全金
            $efficiencyDeposit = $request->efficiency_deposit; // 需要接单赔偿的效率金
            $reason = $request->reason; // 申请协商原因

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->applyConsult($amount, $securityDeposit, $efficiencyDeposit, $reason);
        } catch (DailianException $e) {
            myLog('operate-applyConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-applyConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
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

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->cancelConsult();
        } catch (DailianException $e) {
            myLog('operate-cancelConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-cancelConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
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

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->agreeConsult();
        } catch (DailianException $e) {
            myLog('operate-agreeConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-agreeConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     *  不同意协商
     * @param Request $request
     * @return mixed
     */
    public function refuseConsult(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->refuseConsult();
        } catch (DailianException $e) {
            myLog('operate-agreeConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-agreeConsult', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
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
            $image = $request->image;

            $orderService = OrderServices::init($userId, $orderNo);
            $orderService->applyComplain($reason, $image);
        } catch (DailianException $e) {
            myLog('operate-applyComplain', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            myLog('operate-local-applyComplain', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail('接口异常');
        }
        return response()->apiSuccess();
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
        return response()->apiSuccess();
    }

    /**
     * 接收发单参数，创建订单
     * @param Request $request
     * @return bool
     */
    public function placeOrder(Request $request) {
        DB::beginTransaction();
        try {
            $data = $request->data;
            if (! isset($data) || ! $data) {
                throw new Exception('接收信息为空');
            }
            $decriptData = openssl_decrypt($data, 'aes-128-cbc', static::$key, false, static::$iv);
            $decriptData = json_decode($decriptData);
            $orderService = OrderServices::init(1, '');
            $game = Game::where('name', $data['game_name'])->first();
            $region = Region::where('name', $data['game_region'])->where('game_id', $game->id)->first();
            $server = Server::where('game_server', $data['game_server'])->where('region_id', $region->id)->first();
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
                $data['requirements'],
                $data['businessman_phone'],
                $data['businessman_qq'],
                $data['order_password']
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
                myLog('callback-data', ['result' => $result]);
                if (! isset($result['code']) || $result['code'] != 1) {
                    throw new Exception('回调发单器失败');
                }
            } else {
                throw new Exception('丸子下单失败');
            }
        } catch (Exception $e) {
            DB::rollabck();
            myLog('operate-local-place-order', ['data' => $data, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        }
        DB::commit();
        return response()->apiSuccess();
    }

    /**
     * 合成发单器的sign
     * @param $options
     * @return string
     */
    public function generateSign($options) {
        ksort($options);
        $str = '';
        foreach ($options as $key => $value) {
            $str .= $key . '=' . $value . '&';
        }
        return md5(rtrim($str,  '&') . static::$appSecret);
    }
}
