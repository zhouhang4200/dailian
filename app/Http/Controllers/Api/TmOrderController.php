<?php

namespace App\Http\Controllers\Api;

use App\Services\TmApiService;
use Exception;
use App\Exceptions\OrderException;
use App\Exceptions\UserAssetException;
use App\Models\Game;
use App\Models\Server;
use App\Models\Region;
use App\Models\GameLevelingType;
use App\Models\GameLevelingOrder;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * Class TmOrderController
 * @package App\Http\Controllers\Api
 */
class TmOrderController extends Controller
{
    // 发单器在丸子这边的发单账号ID
    private static $creatorUserId = 1;


    // 允许上传图片类型
    private static $extensions = ['png', 'jpg', 'jpeg', 'gif'];

    /**
     *  上架
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function onSale(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->onSale();
        } catch (OrderException $e) {
            myLog('operate-onSale-error', ['no' => $orderNo, 'message' => $e->getMessage()]);
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
            $orderService = OrderService::init($userId, $orderNo);
            $orderService->cancelConsult();
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
            $orderService->rejectConsult();
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
            $path = public_path("/storage/complain/".date('Ymd')."/");
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

            $orderService = OrderService::init($userId, $orderNo);
            $orderService->applyComplain($reason, $image);
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
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
        $path = strstr($path, '/storage');
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     * 接收发单参数，创建订单
     * @param Request $request
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function placeOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->data;
            if (! isset($data) || ! $data) {
                throw new Exception('接收信息为空');
            }
            $decryptOrderData = TmApiService::decryptOrderData($data);
            $orderService = OrderService::init(config('tm.user_id'));
            $game = Game::where('name', $decryptOrderData['game_name'])->first();
            $region = Region::where('name', $decryptOrderData['game_region'])->where('game_id', $game->id)->first();
            $server = Server::where('name', $decryptOrderData['game_serve'])->where('region_id', $region->id)->first();
            $gameLevelingType = GameLevelingType::where('game_id', $game->id)->where('name', $decryptOrderData['game_leveling_type'])->first();
            // 如果没有找到代练类型
            if (! $gameLevelingType) {
                $gameLevelingType = GameLevelingType::where('game_id', $game->id)->first();
            }

            $order = $orderService->create(
                $game->id,
                $region->id,
                $server->id,
                $decryptOrderData['game_leveling_title'],
                $decryptOrderData['game_account'],
                $decryptOrderData['game_password'],
                $decryptOrderData['game_role'],
                $decryptOrderData['game_leveling_day'],
                $decryptOrderData['game_leveling_hour'],
                $gameLevelingType->id,
                $decryptOrderData['game_leveling_price'],
                $decryptOrderData['game_leveling_security_deposit'],
                $decryptOrderData['game_leveling_efficiency_deposit'],
                $decryptOrderData['game_leveling_instructions'],
                $decryptOrderData['game_leveling_requirements'],
                $decryptOrderData['businessman_phone'],
                $decryptOrderData['creator_username'],
                $decryptOrderData['businessman_phone'],
                $decryptOrderData['businessman_qq'],
                $decryptOrderData['order_password'],
                $decryptOrderData['order_no'],
                1
            );

            // 回调
            if ($order) {
                $callbackResult = TmApiService::callback($decryptOrderData['order_no'], $order->trade_no);
                myLog('call', [$callbackResult, $decryptOrderData]);
                if (! isset($callbackResult['code']) || $callbackResult['code'] != 1) {
                    throw new Exception('调用发单器回调接口失败');
                }

            } else {
                throw new Exception('丸子下单失败');
            }
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常' . $e->getMessage());
        }
        DB::commit();
//        myLog('place-order-success', ['发单器结果' => $result, '从发单器获取的参数' => $data, '发送给发单器的参数' => $options]);
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
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess('成功', $data);
    }

    /**
     *  加时
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function addTime(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;
            $day = $request->day;
            $hour = $request->hour;

            $orderService = OrderService::init($userId, $orderNo);
            $order = $orderService->addTime($day, $hour);
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     *  加价
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function addMoney(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;
            $amount = $request->amount;

            $orderService = OrderService::init($userId, $orderNo);
            $order = $orderService->addMoney($amount);
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     *  修改账号密码
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function updateAccountPassword(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;
            $account = $request->account;
            $password = $request->password;

            $orderService = OrderService::init($userId, $orderNo);
            $order = $orderService->updateAccountPassword($account, $password);
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     * 修改订单
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->data;
            if (! isset($data) || ! $data) {
                throw new Exception('接收信息为空');
            }

            $decryptData = TmApiService::decryptOrderData($data);

            $orderService = OrderService::init(static::$creatorUserId, $decryptData['order_no']);
            $game = Game::where('name', $decryptData['game_name'])->first();
            $region = Region::where('name', $decryptData['game_region'])->where('game_id', $game->id)->first();
            $server = Server::where('name', $decryptData['game_serve'])->where('region_id', $region->id)->first();
            $gameLevelingType = GameLevelingType::where('game_id', $game->id)->first();

            $order = $orderService->update(
                $game->id,
                $region->id,
                $server->id,
                $decryptData['game_leveling_title'],
                $decryptData['game_account'],
                $decryptData['game_password'],
                $decryptData['game_role'],
                $decryptData['game_leveling_day'],
                $decryptData['game_leveling_hour'],
                $gameLevelingType->id,
                $decryptData['game_leveling_price'],
                $decryptData['game_leveling_security_deposit'],
                $decryptData['game_leveling_efficiency_deposit'],
                $decryptData['game_leveling_instructions'],
                $decryptData['game_leveling_requirements'],
                $decryptData['businessman_phone'],
                $decryptData['businessman_qq'],
                $decryptData['order_password']
            );
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        DB::commit();
        return response()->apiSuccess();
    }

    /**
     * 获取完成图片
     */
    public function applyCompleteImage(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderService::init($userId, $orderNo);
            $images = $orderService->applyCompleteImage();
            $order = GameLevelingOrder::where('trade_no', $orderNo)->first();

            $data = [];
            foreach ($images as $k => $image) {
                $data[$k]['username'] = $order->take_username ?? '';
                $data[$k]['created_at'] = $order->created_at;
                $data[$k]['url'] = asset($image->path);
            }

        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess('操作成功', $data);
    }

    /**
     *  添加仲裁证据
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function complainMessage(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;
            $image = $request->image;
            $reason = $request->reason;

            $orderService = OrderService::init($userId, $orderNo);
            $order = $orderService->sendComplainMessage(base64ToImg($image, 'complain'), $reason);
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     *  获取订单留言
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function getMessage(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderService::init($userId, $orderNo);
            $messages = $orderService->getMessage();

            $data = [];
            $initiator = ['1' => '发单方', '2' => '接单方', '3' => '工作人员'];
            foreach($messages as $k => $message) {
                $data[$k]['sender'] = $initiator[$message->initiator];
                $data[$k]['id'] = $message->id;
                $data[$k]['send_content'] = $message->content;
                $data[$k]['send_time'] = $message->created_at;
            }
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess('操作成功', $data);
    }

    /**
     *  发送留言
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function sendMessage(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;
            $message = $request->message;

            $orderService = OrderService::init($userId, $orderNo);
            $order = $orderService->sendMessage($message);
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }

    /**
     *  获取仲裁信息
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function getComplainInfo(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderService::init($userId, $orderNo);
            $order = $orderService->getComplainInfo();
            $data = [];
            $complain = $order->complain;
            $data['who'] = $complain->initiator;
            $data['created_at'] = $complain->created_at;
            $data['content'] = $complain->reason;
            $data['arbitration_id'] = $complain->id;
            $images = $complain->image;
            $data['image']['pic1'] = '';
            $data['image']['pic2'] = '';
            $data['image']['pic3'] = '';

            foreach($images as $k => $image) {
                if ($k == 0) {
                    $data['image']['pic1'] = asset($image->path);
                }
                if ($k == 1) {
                    $data['image']['pic2'] = asset($image->path);
                }
                if ($k == 2) {
                    $data['image']['pic3'] = asset($image->path);
                }
            }
            if (isset($order->complain->messages)) {
                foreach ($order->complain->messages as $k => $message) {
                    $data['message'][$k]['pic'] = '';
                    $data['message'][$k]['who'] = $message->initiator;
                    $data['message'][$k]['created_at'] = $message->created_at;
                    $data['message'][$k]['content'] = $message->content;
                    if (isset($message->image)) {
                        foreach ($message->image as $k1 => $image) {
                            $data['message'][$k]['pic'] = asset($image->path);
                        }
                    }
                }
            }
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess('成功', $data);
    }

    /**
     *  发送留言
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function top(Request $request)
    {
        try {
            $orderNo = $request->order_no;
            $userId = $request->user->id;

            $orderService = OrderService::init($userId, $orderNo);
            $order = $orderService->top();
        } catch (OrderException $e) {
            return response()->apiFail($e->getMessage());
        } catch (UserAssetException $e) {
            return response()->apiFail($e->getMessage());
        } catch (Exception $e) {
            return response()->apiFail('接单平台接口异常');
        }
        return response()->apiSuccess();
    }
}
