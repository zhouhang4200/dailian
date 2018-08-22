<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Exception;
use App\Models\RealNameCertification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     *  小程序首页个人资料
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $datas['id'] = $user->id;
            $datas['name'] = $user->name;
            $datas['phone'] = $user->phone;
            $datas['balance'] = $user->userAsset->balance;
            $datas['frozen'] = $user->userAsset->frozen;
            $datas['total_balance'] = bcadd($datas['balance'], $datas['frozen'], 2);

            $data = config('api.code')['0'];
            $data['data'] = [$datas];
            return response()->json($data);
        } catch (Exception $e) {
            $data = config('api.code')['2001'];
            $data['data'] = [];
            myLog('wx-profile-index', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->json($data);
        }
    }

    /**
     *  修改资料
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $user->name = request('name');
            $user->name = request('email');
            $user->qq = request('qq');
            $user->save();

            return response()->apiSuccess();
        } catch (Exception $e) {
            myLog('wx-profile-update', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiFail('丸子接口异常');
        }
    }
}
