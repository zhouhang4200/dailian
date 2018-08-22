<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Exception;
use App\Models\UserAssetFlow;
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

            $userAssetFlows = UserAssetFlow::where('user_id', $user->parent_id)->latest('created_at')->get();

            $data = [];
            foreach ($userAssetFlows as $k => $userAssetFlow) {
                $data[$k]['id'] = $userAssetFlow->id;
                $data[$k]['type'] = config('user_asset.type')[$userAssetFlow->type];
                $data[$k]['amount'] = $userAssetFlow->amount;
                $data[$k]['created_at'] = $userAssetFlow->created_at->toDateTimeString();
            }

            return response()->apiJson(0, $data);
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
            $data['created_at'] = $userAssetFlow->created_at->toDateTimeString();
            $data['remark'] = $userAssetFlow->remark;

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-profile-flowsShow-error', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
