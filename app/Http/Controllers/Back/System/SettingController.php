<?php

namespace App\Http\Controllers\Back\System;

use App\Http\Controllers\Controller;
use Unisharp\Setting\SettingFacade;

/**
 * Class SettingController
 * @package App\Http\Controllers\Back
 */
class SettingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $key = request('key', 'withdraw');

        return view('back.system.setting.' . $key)->with([
            'key' => $key,
            'value' => SettingFacade::get($key),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        // 推广比例大于1，返回失败
        if (request('key') == 'spread' && request('spread') > 1) {
            return back();
        }

        SettingFacade::set(request('key'), request()->except('_token'));

        return back();
    }
}
