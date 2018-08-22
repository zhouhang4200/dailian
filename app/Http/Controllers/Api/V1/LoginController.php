<?php

namespace App\Http\Controllers\Api\V1;

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

            $data = config('api.code')['0'];
            $data['data'] = ['token' => $user->createToken('WanZiXiaoChengXu')->accessToken];

        } else {
            $data = config('api.code')['1004'];
            $data['data'] = [];
        }
        return response()->json($data);
    }
}
