<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UserAssetException;
use App\Exceptions\UserException;
use App\Services\UserBalanceService;
use DB;
use Auth;
use Exception;
use App\Services\UserAssetService;
use App\Models\BalanceRecharge;
use App\Models\UserAssetFlow;
use Yansongda\Pay\Pay;
use Unisharp\Setting\SettingFacade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 *  资金管理
 * Class FinanceController
 * @package App\Http\Controllers\Api\V1
 */
class FinanceController extends Controller
{
    /**
     *  资金流水
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function flows(Request $request)
    {
        try {
            $user = Auth::user();

            // 查看资金流水权限
            if (! $user->could('finance.asset-flow')) {
                return response()->apiJson(1005);
            }

            $userAssetFlows = UserAssetFlow::where('user_id', $user->parent_id)
                ->select(['id', 'type', 'amount', 'balance', 'created_at'])
                ->latest('id')->paginate(request('page_size', 20));

            return response()->apiJson(0, [
                'total' => $userAssetFlows->total(),
                'total_page' => $userAssetFlows->lastPage(),
                'current_page' => $userAssetFlows->currentPage(),
                'page_size' => $userAssetFlows->perPage(),
                'list' => $userAssetFlows->items()
            ]);
        } catch (Exception $e) {
            myLog('wx-profile-flows-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  资金流水详情
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function flowsShow(Request $request)
    {
        try {
            $user = Auth::user();

            // 查看资金流水权限
            if (! $user->could('finance.asset-flow')) {
                return response()->apiJson(1005);
            }

            if (is_null(request('id'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            $userAssetFlow = UserAssetFlow::find(request('id'));

            $data['type'] = config('user_asset.type')[$userAssetFlow->type];
            $data['amount'] = $userAssetFlow->amount;
            $data['created_at'] = $userAssetFlow->created_at;
            $data['remark'] = $userAssetFlow->remark;

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-profile-flowsShow-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  提现
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function withdraw(Request $request)
    {
        $user = auth()->user()->parent;

        // 提现权限
        if (! $user->could('finance.balance-withdraw')) {
            return response()->apiJson(1005);
        }

        if (is_null(request('amount'))) {
            return response()->apiJson(1001); // 参数缺失
        }

        DB::beginTransaction();
        try {
            // 生成提现单
            $record = UserBalanceService::withdraw(
                request('amount'),
                $user,
                request('alipay_account'),
                request('alipay_name')
            );
            // 冻结资金
            UserAssetService::init(35, $record->user_id, $record->amount, $record->trade_no)->frozen();
        } catch (UserException $exception) {
            return response()->apiJson($exception->getCode());
        }  catch (UserAssetException $exception) {
            return response()->apiJson($exception->getCode());
        }  catch (\Exception $exception) {
            return response()->apiJson(1003);
        }
        DB::commit();

        return response()->apiJson(0);
    }

    /**
     *  充值
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function recharge(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            if (is_null(request('amount'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            // 生成充值单号
            $tradeNo = generateOrderNo();

            BalanceRecharge::create([
                'user_id' => $user->parent_id,
                'amount' => request('amount'),
                'trade_no' => $tradeNo,
                'source' => 1
            ]);

            $order = [
                'out_trade_no' => $tradeNo,
                'total_fee' => bcmul(request('amount'), 100, 0), // 单位：分
                'body' => '丸子代练-账户余额充值',
                'notify_url' => route('api.finance.wechat-notify'),
                'openid' => $user->wechat_open_id,
            ];

            $result = Pay::wechat(config('pay.wechat'))->miniapp($order);

            if (! $result) {
                return response()->apiJson(1003);
            }
        } catch (Exception $e) {
            myLog('wx-profile-recharge-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
        DB::commit();

        return response()->apiJson(0, $result);
    }

    /**
     * 微信异步回调
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function wechatNotify()
    {
        $wechat = Pay::wechat(config('pay.wechat'));

        try{
            $data = $wechat->verify();
            myLog('wx-notify', [$data]);
            // 支付宝确认交易成功
            if ($data->result_code == 'SUCCESS') {
                // 查找充值订单为用户加款
                $order = BalanceRecharge::where('trade_no', $data->out_trade_no)
                    ->where('amount', bcdiv($data->cash_fee, 100))
                    ->where('source', 1)
                    ->where('status', 1)
                    ->first();
                // 查到充值订单
                if ($order) {
                    // 为用户加款
                    UserAssetService::init(11, $order->user_id, $order->amount, $order->trade_no)->recharge();
                    $order->status = 2;
                    $order->save();
                }
            }
        } catch (Exception $e) {
            \Log::debug('Wechat notify Error', [$e->getMessage()]);
        }

        return $wechat->success();
    }

    /**
     *  提现信息
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function withdrawInfo(Request $request)
    {
        try {
            $user = Auth::user();
            // 默认手续费 1%
            $rate =  \Setting::get('withdraw.rate') ? \Setting::get('withdraw.rate') : 1;

            $data = [
                'balance' => auth()->user()->userAsset->balance,
                'rate' => bcdiv($rate, 100, 2),
                'tips' => SettingFacade::get('withdraw.tips'),
                'min_amount' => SettingFacade::get('withdraw.min_amount'),
                'max_amount' => SettingFacade::get('withdraw.max_amount'),
            ];

        } catch (Exception $e) {
            myLog('wx-profile-withdrawInfo-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }

        return response()->apiJson(0, $data);
    }
}
