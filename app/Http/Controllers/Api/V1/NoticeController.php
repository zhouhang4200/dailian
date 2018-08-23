<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Exception;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 *  公告中心
 * Class NoticeController
 * @package App\Http\Controllers\Api\V1
 */
class NoticeController extends Controller
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
            $categoryIds = ArticleCategory::where('parent_id', 1)->where('status', 1)->oldest('sort')->pluck('id');

            if (empty($categoryIds)) {
                return response()->apiJson(0);
            }

            $articles = Article::where('status', 1)
                ->whereIn('article_category_id', $categoryIds)
                ->oldest('sort')
                ->get();

            if ($articles->count() < 1) {
                return response()->apiJson(0);
            }

            foreach ($articles as $k => $article) {
                $data[$k]['id'] = $article->id;
                $data[$k]['title'] = $article->title;
            }
            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-notice-index-error', ['失败:' => $e->getMessage()]);
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
            $article = Article::find(request('id'));

            if (! $article) {
                return response()->apiJson(5001);
            }

            $data['id'] = $article->id;
            $data['title'] = $article->title;
            $data['content'] = $article->content;
            $data['created_at'] = $article->created_at->toDateTimeString();

            return response()->apiJson(0, $data);
        } catch (Exception $e) {
            myLog('wx-notice-show-error', ['失败:' => $e->getMessage()]);
            return response()->apiJson(1003);
        }
    }
}
