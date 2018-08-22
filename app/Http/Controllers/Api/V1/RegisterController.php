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
                return response()->apiJson(1001);
            }

            // 手机号已存在
            if (User::where('phone', request('phone'))->first()) {
                return response()->apiJson(2003);
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

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'wechat' => $user->wechat,
                'qq' => $user->qq,
                'avatar' => $user->avatar,
                'status' => $user->status,
                'token' => $user->createToken('WanZiXiaoChengXu')->accessToken,
            ];
            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-register-error', ['失败原因：' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
