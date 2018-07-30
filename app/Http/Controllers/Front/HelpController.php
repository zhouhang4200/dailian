<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    public function index(Request $request)
    {
        return view('front.help.index');
    }
}
