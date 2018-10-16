<?php

namespace App\Http\Controllers\Front;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 帮助中心
 * Class HelpController
 * @package App\Http\Controllers\Front
 */
class HelpController extends Controller
{
    /**
     * 帮助中心页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories = ArticleCategory::where('status', 1)
            ->where('parent_id', 2)
            ->oldest('sort')
            ->with(['articles' => function ($query) {
                $query->oldest('sort')->where('status', 1);
            }])
            ->get();

        return view('front.help.index', compact('categories'));
    }
}
