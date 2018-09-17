<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\User;
use App\Services\TmApiService;
use App\Exceptions\OrderException;
use App\Exceptions\UserAssetException;
use App\Models\GameLevelingOrder;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Validator;

/**
 * 订单操作
 * Class OrderOperationController
 * @package App\Http\Controllers\Api\V1
 */
class OrderOperationController extends Controller
{
    /**
     * 接单
     * @return mixed
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function take()
    {
        if (! request('trade_no') || ! request('pay_password')) {
            response()->apiJson(1001);
        }
        DB::beginTransaction();
        $order = null;
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->take(clientRSADecrypt(request('pay_password')), clientRSADecrypt(request('take_password')));
            $order = GameLevelingOrder::where('trade_no', request('trade_no'))->first();
            TmApiService::take($order);
        } catch (OrderException $exception) {
            $order->statrus = 1;
            $order->save();
            return response()->apiJson($exception->getCode());
        } catch (UserAssetException $exception) {
            $order->statrus = 1;
            $order->save();
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            $order->statrus = 1;
            $order->save();
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 申请验收
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function applyComplete()
    {

        if (! request('trade_no') || ! request('images')) {
           return response()->apiJson(1001);
        }

        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->applyComplete(imageJsonToArr(request('images')));
            TmApiService::applyComplete(request('trade_no'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        }  catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 查看验收图片
     * @return mixed
     */
    public function applyCompleteImage()
    {
        if (! request('trade_no')) {
            response()->apiJson(1001);
        }

        try {
          $images = OrderService::init(request()->user()->id, request('trade_no'))->applyCompleteImage();
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0, $images->toArray());
    }

    /**
     * 取消验收
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelComplete()
    {
        if (! request('trade_no')) {
            response()->apiJson(1001);
        }
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->cancelComplete();
            TmApiService::cancelComplete(request('trade_no'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 申请撤销
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function applyConsult()
    {
        $validator = Validator::make(request()->all(), [
            'trade_no' => 'required',
            'amount' => 'required',
            'deposit' => 'required',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->apiJson(1001);
        }

        // 拆分保证金
        $deposit = GameLevelingOrder::deuceDeposit(request('trade_no'), request('deposit'));
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))
                ->applyConsult(request('amount'), $deposit['security_deposit'], $deposit['efficiency_deposit'], request('reason'));

            TmApiService::applyConsult(request('trade_no'), request('amount'), request('deposit'), request('reason'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);

    }

    /**
     * 取消撤销
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelConsult()
    {
        if (! request('trade_no')) {
            response()->apiJson(1001);
        }
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->cancelConsult();

            TmApiService::cancelConsult(request('trade_no'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 同意撤销
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function agreeConsult()
    {
        if (! request('trade_no')) {
            response()->apiJson(1001);
        }
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->agreeConsult();

            TmApiService::agreeConsult(request('trade_no'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 不同意撤销
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function rejectConsult()
    {
        if (! request('trade_no')) {
            response()->apiJson(1001);
        }
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->rejectConsult();

            TmApiService::rejectConsult(request('trade_no'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 申请仲裁
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function applyComplain()
    {
        if (! request('trade_no') || ! request('reason') || ! request('images')) {
            return response()->apiJson(1001);
        }
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->applyComplain(request('reason'), imageJsonToArr(request('images')));

            TmApiService::applyComplain(request('trade_no'), request('reason'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 取消仲裁
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelComplain()
    {
        if (! request('trade_no')) {
            return response()->apiJson(1001);
        }
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->cancelComplain();

            TmApiService::cancelComplain(request('trade_no'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 获取仲裁信息
     * @return mixed
     */
    public function getComplainInfo()
    {
        if (! request('trade_no')) {
            return response()->apiJson(1001);
        }

        try {
           $complainInfo = OrderService::init(request()->user()->id, request('trade_no'))->getComplainInfo();
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }

        $responseData = [
            'initiator' => $complainInfo->complain->initiator,
            'reason' => $complainInfo->complain->reason,
            'created_at' => $complainInfo->complain->created_at,
            'images' => $complainInfo->complain->image()->select('path')->get()->pluck('path')->toArray(),
        ];

        $messages = [];
        foreach ($complainInfo->complain->messages as $item) {

            $path = '';
            if (count($item->image)) {
                $path = $item->image[0]->path;
            }
            $userInfo = User::find($item->from_user_id);

            $messages[] = [
                'initiator' => $item->initiator,
                'content' => $item->content,
                'path' => $path,
                'name' => $userInfo->name,
                'avatar' => $userInfo->avatar,
                'created_at' => $item->created_at,
            ];
        }
        $responseData['messages'] = $messages;

        return response()->apiJson(0, $responseData);
    }

    /**
     * 发送仲裁信息
     * @return mixed
     * @throws Exception
     */
    public function sendComplainMessage()
    {
        if (! request('trade_no') || ! request('content')) {
            return response()->apiJson(1001);
        }

        try {
            OrderService::init(request()->user()->id, request('trade_no'))
                ->sendComplainMessage(request('image') ? ['path' => request('image')] :  [], request('content'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }

        return response()->apiJson(0);
    }

    /**
     * 获取留言
     * @return mixed
     */
    public function getMessage()
    {
        if (! request('trade_no')) {
            return response()->apiJson(1001);
        }

        try {
           $messages =  OrderService::init(request()->user()->id, request('trade_no'))->getMessage();
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }

        $responseData = $messages->map(function($item) {
           return  [
                'initiator' => $item->initiator,
                'content' => $item->content,
                'created_at' => $item->created_at,
                'avatar' => User::where('id',  $item->from_user_id)->value('avatar')
            ];
        });
        return response()->apiJson(0, $responseData);
    }

    /**
     * 发送订单留言
     * @return mixed
     */
    public function sendMessage()
    {
        if (! request('trade_no') || ! request('content')) {
            return response()->apiJson(1001);
        }

        try {
            $message = OrderService::init(request()->user()->id, request('trade_no'))->sendMessage(request('content'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }

        return response()->apiJson(0, [
            'initiator' => $message->initiator,
            'content' => $message->content,
            'created_at' => $message->created_at,
            'avatar' => User::where('id',  $message->from_user_id)->value('avatar')
        ]);
    }

    /**
     * 异常
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function anomaly()
    {
        if (! request('trade_no') || ! request('reason')) {
            return response()->apiJson(1001);
        }
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->anomaly(request('reason'));

            TmApiService::anomaly(request('trade_no'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        }catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }

    /**
     * 取消异常
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelAnomaly()
    {
        if (! request('trade_no')) {
            return response()->apiJson(1001);
        }
        DB::beginTransaction();
        try {
            OrderService::init(request()->user()->id, request('trade_no'))->cancelAnomaly();

            TmApiService::cancelAnomaly(request('trade_no'));
        } catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (OrderException $exception) {
            return response()->apiJson($exception->getCode());
        } catch (Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0);
    }
}
