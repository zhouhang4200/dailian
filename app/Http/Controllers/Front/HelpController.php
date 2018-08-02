<?php

namespace App\Http\Controllers\Front;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    public function index(Request $request)
    {
        $categories = ArticleCategory::where('status', 1)->where('parent_id', 2)->oldest('sort')->with(['articles' => function ($query) {
            $query->oldest('sort')->where('status', 1);
        }])->get();

        return view('front.help.index', compact('categories'));
    }
}
