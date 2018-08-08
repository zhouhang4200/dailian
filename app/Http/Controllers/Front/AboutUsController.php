<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class AboutUsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('front.about-us.index');
    }
}
