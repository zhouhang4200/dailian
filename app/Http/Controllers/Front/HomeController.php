<?php

namespace App\Http\Controllers\Front;

use App\Exceptions\UnknownException;
use App\Models\GameLevelingOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('front.home.index');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function miniProgramTokenCheck()
    {

        $tmpArr = array(config('wechat.mini_program.default.token'), request('timestamp'), request('nonce'));
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == request('signature')) {
            echo request('echostr');
        } else {
            echo false;
        }
    }
}
