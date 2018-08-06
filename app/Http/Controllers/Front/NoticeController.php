<?php

namespace App\Http\Controllers\Front;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $categories = ArticleCategory::where('status', 1)->where('parent_id', 1)->oldest('sort')->with(['articles' => function ($query) {
            $query->oldest('sort')->where('status', 1);
        }])->get();

        return view('front.notice.index', compact('categories'));
    }
}
