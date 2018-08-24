<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Hash;
use Auth;
use Redis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    /**
     *  找回密码
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function refund(Request $request)
    {
        try {
            if (is_null(request('phone')) || is_null(request('new_password')) || is_null(request('verification_code'))) {
                return response()->apiJson(1001); // 参数缺失
            }
            $code = Redis::get("user:verification-code:".request('phone'));
            if (request('verification_code') != $code) {
                return response()->apiJson(1006); // 验证码错误
            }

            $user = Auth::user();

            $user->password = bcrypt(request('new_password'));
            $user->save();
            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-refund-error', ['用户:' => $user->id ?? '', '失败原因：' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  重置密码
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function reset(Request $request)
    {
        try {
            if (is_null(request('old_password')) || is_null(request('new_password'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            $user = Auth::user();
            if (! Hash::check(request('new_password'), $user->password)) {
                return response()->apiJson(2005); // 原密码错误
            }
            $user->password = bcrypt(request('new_password'));
            $user->save();
            return response()->apiJson(0);
        } catch (Exception $e) {
            myLog('wx-reset-error', ['用户:' => $user->id ?? '', '失败原因：' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
