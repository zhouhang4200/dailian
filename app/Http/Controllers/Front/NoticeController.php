<?php

namespace App\Http\Controllers\Front;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 公告中心
 * Class NoticeController
 * @package App\Http\Controllers\Front
 */
class NoticeController extends Controller
{
    /**
     * 公告中心页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = ArticleCategory::where('status', 1)
            ->where('parent_id', 1)
            ->oldest('sort')
            ->with(['articles' => function ($query) {
                $query->oldest('sort')->where('status', 1);
            }])
            ->get();

        return view('front.notice.index', compact('categories'));
    }
}
