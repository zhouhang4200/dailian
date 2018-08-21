<?php

namespace App\Http\Controllers\Api;

use Auth;
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
        if(Auth::attempt(['phone' => request('phone'), 'password' => request('password')]))
        {
            $user = Auth::user();

            $datas = [];
            $datas['token'] = $user->createToken('WanZiXiaoChengXu')->accessToken;
            $status = 200;
        } else {
            $datas['error'] = "未授权";
            $status = 401;
        }
        return response()->json($datas, $status);
    }
}
