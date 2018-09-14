<?php

namespace App\Http\Controllers\Front;

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
     * @return bool
     */
    public function miniProgramTokenCheck()
    {
        $tmpArr = array('MM5+sPHXeqLhu2BTXgGRjfp5iJ9dqFCe', request('timestamp'), request('nonce'));
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == request('signature')) {
            return true;
        } else {
            return false;
        }
    }
}
