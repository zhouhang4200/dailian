<?php

namespace App\Http\Controllers\Front;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        return view('front.notice.index');
    }
}
