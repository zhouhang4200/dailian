<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     *  小程序登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // 参数缺失
            if (is_null( request('phone')) || is_null(request('password'))) {
                return response()->apiJson(1001);
            }

            if(Auth::attempt(['phone' => request('phone'), 'password' => request('password')])) {
                $user = Auth::user();
                $data = [
                    'name' => $user->name,
                    'age' => $user->age,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'wechat' => $user->wechat,
                    'qq' => $user->qq,
                    'avatar' => $user->avatar,
                    'status' => $user->status,
                    'token' => $user->createToken('WanZiXiaoChengXu')->accessToken
                ];
                return response()->apiJson(0, $data);
            } else {
                return response()->apiJson(1004);
            }
        } catch (Exception $e) {
            myLog('wx-login-error', ['失败原因：' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
