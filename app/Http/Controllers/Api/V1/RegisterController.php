<?php

namespace App\Http\Controllers\Api\V1;

use Redis;
use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            // 参数缺失
            if (is_null(request('phone')) || is_null(request('password')) || is_null(request('verification_code'))) {
                return response()->apiJson(1001);
            }

            if (strlen(request('password')) < 6 || strlen(request('password')) > 20) {
                return response()->apiJson(2010); // 密码长度
            }

            // 手机号已存在
            if (User::where('phone', request('phone'))->first()) {
                return response()->apiJson(2003);
            }

            $code = Redis::get("user:verification-code:".request('phone'));
            if (request('verification_code') != $code) {
                return response()->apiJson(1006); // 验证码错误
            }

            $datas = [];
            $datas['phone'] = request('phone');
            $datas['password'] = bcrypt(request('password'));
            $datas['pay_password'] = bcrypt(request('password'));
            $datas['name'] = request('name') ?? str_random(5);
            $datas['email'] = request('email') ?? str_random(7)."@163.com";
            $datas['avatar'] = asset('/front/images/default_avatar.png');
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
                'token' => $user->createToken('WanZiXiaoChengXu')->accessToken
            ];

            $role = Role::where('name', 'default')->where('user_id', 0)->first();
            $user->roles()->sync($role->id);

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-register-error', ['失败原因：' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
