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

            $data['id'] = $user->id;
            $data['name'] = $user->name;
            $data['phone'] = $user->phone;
            $data['balance'] = $user->userAsset ? $user->userAsset->balance : 0;
            $data['frozen'] = $user->userAsset ? $user->userAsset->frozen : 0;

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-profile-index', ['用户:' => $user->id ?? '', '失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
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
