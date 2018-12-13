<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JdOrder;

class TempController extends Controller
{

    public function jdOrder()
    {
        $arr = [];
        preg_match_all('/\d+/',request('order_no'),$arr);

        if ($arr[0][0]) {
            JdOrder::create([
                'order_no' => $arr[0][0]
            ]);
        }
    }
}