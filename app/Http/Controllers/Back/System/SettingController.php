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
    public function index()
    {
        $key = request('key', 'withdraw');

        return view('back.setting.index')->with([
            'key' => $key,
            'value' => SettingFacade::get($key),
        ]);
    }

    public function update()
    {
        SettingFacade::set(request('key'), request()->except('_token'));
        return back();
    }
}
