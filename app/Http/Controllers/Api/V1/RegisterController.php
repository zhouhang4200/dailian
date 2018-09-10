<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use Redis;
use Exception;
use App\Models\Role;
use App\Models\UserAsset;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $password = clientRSADecrypt(request('password'));
            // 参数缺失
            if (is_null(request('phone')) || is_null($password) || is_null(request('verification_code'))) {
                return response()->apiJson(1001);
            }

            if (strlen($password) < 6 || strlen($password) > 20) {
                return response()->apiJson(2010); // 密码长度
            }

            // 手机号已存在
            if (User::where('phone', request('phone'))->first()) {
                return response()->apiJson(2003);
            }

            $code = Redis::get("user:verification-code:".request('phone'));

            if (! $code) {
                return response()->apiJson(1009); // 验证码已过期
            }
            if (request('verification_code') != $code) {
                return response()->apiJson(1006); // 验证码错误
            }

            $datas = [];
            $datas['phone'] = request('phone');
            $datas['password'] = bcrypt($password);
            $datas['name'] = request('name') ?? str_random(5);
            $datas['avatar'] = asset('/front/images/default_avatar.png');
            $datas['status'] = 1;

            $user = User::create($datas);

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'wechat' => $user->wechat ?? '',
                'qq' => $user->qq ?? '',
                'avatar' => $user->avatar,
                'status' => $user->status,
                'token' => $user->createToken('WanZiXiaoChengXu')->accessToken
            ];

            $role = Role::where('name', 'default')->where('user_id', 0)->first();
            $user->roles()->sync($role->id);

            // 初始化用户资产
            UserAsset::create(['user_id' => $user->id]);
        } catch (Exception $e) {
            myLog('wx-register-error', ['失败原因：' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
        DB::commit();
        return response()->apiJson(0, $data);
    }
}
