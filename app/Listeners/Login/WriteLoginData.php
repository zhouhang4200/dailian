<?php

namespace App\Listeners\Login;

use Exception;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class WriteLoginData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login $event
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Login $event)
    {
        try {
            $ip = getIp(); // 当前登录IP
            $user = $event->user;
            $user->last_login_local = $user->current_login_local ?? null;
            $user->last_login_at = $user->current_login_at ?? null;
            $user->last_login_ip = $user->current_login_ip ?? null;
            $user->current_login_local = getLoginCity($ip) ?: null;
            $user->current_login_at = Carbon::now()->toDateTimeString();
            $user->current_login_ip = ip2long($ip) ?: null;

            $user->save();
        } catch (Exception $e) {
            myLog('login.city.error', ['status' => 0, 'message' => '获取登录城市失败:'.$e->getMessage(), 'user_id' => $event->user ? $event->user->id : '']);
        }
    }
}