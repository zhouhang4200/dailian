<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            // 参数缺失
            if (is_null(request('phone')) || is_null(request('password'))) {
                $data = config('api.code')['1001'];
                $data['data'] = [];
                return response()->json($data);
            }
            // 已存在
            if (User::where('phone', request('phone'))->first()) {
                $data = config('api.code')['2003'];
                $data['data'] = [];
                return response()->json($data);
            }
            $datas = [];
            $datas['phone'] = request('phone');
            $datas['password'] = bcrypt(request('password'));
            $datas['pay_password'] = bcrypt(request('password'));
            $datas['name'] = request('name') ?? str_random(5);
            $datas['email'] = request('email') ?? str_random(7)."@163.com";
            $datas['avatar'] = '/front/images/default_avatar.png';
            $datas['status'] = 1;

            $user = User::create($datas);

            $data = [];
//            $data = config('api.code')['0'];
            $data['data'] = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'wechat' => $user->wechat,
                'qq' => $user->qq,
                'avatar' => $user->avatar,
                'status' => $user->status,
                'token' => $user->createToken('WanZiXiaoChengXu')->accessToken,
            ];
        } catch (Exception $e) {
//            $data = config('api.code')['1003'];
            $data['data'] = [];
            myLog('wx-register-error', ['失败原因：' => $e->getMessage()]);
        }
        return response()->apiJson(1003, $data);
    }
}
