<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Exception;
use App\Models\ArticleCategory;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    /**
     *  公告列表
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request)
    {
        try {
            $categories = ArticleCategory::where('parent_id', 2)->where('status', 1)->oldest('sort')->get();

            if ($categories->count() < 1) {
                return response()->apiJson(0);
            }

            foreach ($categories as $k => $category) {
                $data[$k]['id'] = $category->id;
                $data[$k]['name'] = $category->name;
            }
            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-help-index-error', ['失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }

    /**
     *  公告详情
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function show(Request $request)
    {
        try {
            if (is_null(request('id'))) {
                return response()->apiJson(1001); // 参数缺失
            }

            $category = ArticleCategory::find(request('id'));

            if (! $category) {
                return response()->apiJson(5002);
            }
            $articles = $category->articles;

            if ($articles->count() < 1) {
                return response()->apiJson(0);
            }

            foreach ($articles as $k => $article) {
                $data['id'] = $article->id;
                $data['title'] = $article->title;
                $data['content'] = $article->content;
                $data['created_at'] = $article->created_at->toDateTimeString();
            }

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-help-show-error', ['失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
