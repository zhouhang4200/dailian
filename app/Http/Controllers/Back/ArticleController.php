<?php

namespace App\Http\Controllers\Back;

use DB;
use Exception;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 帮助,公告中心
 * Class ArticleController
 * @package App\Http\Controllers\Back
 */
class ArticleController extends Controller
{
    /**
     * 公告列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function noticeIndex(Request $request)
    {
        $title = $request->title;
        $filters = compact('title');

        $categoryId = $request->category_id;

        $categories = ArticleCategory::where('parent_id', 1)->where('status', 1)->oldest('sort')->get(); // 公告

        $articles = Article::filter($filters)->oldest('sort')->where('status', 1)->whereHas('articleCategory', function ($query) use ($categoryId) {
            $query->where('id', $categoryId);
        })->paginate();

        if ($request->ajax()) {
            $articles = Article::oldest('sort')->where('status', 1)->whereHas('articleCategory', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })->paginate();

            return response()->json(view()->make('back.article.notice.list', compact('categories', 'articles', 'categoryId', 'title'))->render());
        }

        return view('back.article.notice.index', compact('articles', 'categories', 'categoryId', 'title'));
    }

    /**
     * 公告添加视图
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function noticeCreate(Request $request)
    {
        $categoryId = $request->category_id;

        return view('back.article.notice.create', compact('categoryId' ));

    }

    /**
     * 公告添加
     * @param Request $request
     * @return mixed
     */
    public function noticeStore(Request $request)
    {
        try {
            $data = $request->data;

            $data['link'] = '';
            $data['click_count'] = 0;
            // 排序
            $hasSameSort = Article::where('sort', $data['sort'])->first();
            if ($hasSameSort) {
                $otherArticles = Article::where('sort', '>=', $data['sort'])->get();

                foreach ($otherArticles as $otherArticle) {
                    $otherArticle->sort = $otherArticle->sort + 1;
                    $otherArticle->save();
                }
            }
            Article::create($data);

            return response()->ajaxSuccess('添加成功');
        } catch (Exception $e) {
            return response()->ajaxFail('添加失败');
        }
    }

    /**
     * 公告修改视图
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function noticeEdit(Request $request, $id)
    {
        $categoryId = $request->category_id;

        $article = Article::find($id);

        return view('back.article.notice.edit', compact('categoryId', 'article'));
    }

    /**
     * 公告修改
     * @param Request $request
     * @return mixed
     */
    public function noticeUpdate(Request $request)
    {
        try {
            $article = Article::find($request->id);
            $data = $request->data;
            // 排序
            $hasSameSort = Article::where('sort', $data['sort'])->first();
            if ($hasSameSort) {
                $otherArticles = Article::where('sort', '>=', $data['sort'])->get();

                foreach ($otherArticles as $otherArticle) {
                    $otherArticle->sort = $otherArticle->sort + 1;
                    $otherArticle->save();
                }
            }
            $article->update($data);

            return response()->ajaxSuccess('修改成功');
        } catch (Exception $e) {
            return response()->ajaxFail('修改失败');
        }
    }

    /**
     * 公告删除
     * @param Request $request
     * @return mixed
     */
    public function noticeDelete(Request $request)
    {
        try {
            $article = Article::find($request->id);
            $article->delete();

            return response()->ajaxSuccess('删除成功');
        } catch (Exception $e) {
            return response()->ajaxFail('删除失败');
        }
    }

    /**
     * 帮助列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function helpIndex(Request $request)
    {
        $title = $request->title;
        $filters = compact('title');

        $categoryId = $request->category_id;

        $categories = ArticleCategory::where('parent_id', 2)->where('status', 1)->oldest('sort')->get(); // 公告

        $articles = Article::filter($filters)->oldest('sort')->where('status', 1)->whereHas('articleCategory', function ($query) use ($categoryId) {
            $query->where('id', $categoryId);
        })->paginate();

        if ($request->ajax()) {
            $articles = Article::oldest('sort')->where('status', 1)->whereHas('articleCategory', function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })->paginate();

            return response()->json(view()->make('back.article.notice.list', compact('categories', 'articles', 'categoryId', 'title'))->render());
        }

        return view('back.article.help.index', compact('articles', 'categories', 'categoryId', 'title'));
    }

    /**
     * 帮助添加视图
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function helpCreate(Request $request)
    {
        $categoryId = $request->category_id;

        return view('back.article.help.create', compact('categoryId'));
    }

    /**
     * 帮助添加
     * @param Request $request
     * @return mixed
     */
    public function helpStore(Request $request)
    {
        try {
            $data = $request->data;
            $data['link'] = '';
            $data['click_count'] = 0;
            // 排序
            $hasSameSort = Article::where('sort', $data['sort'])->first();
            if ($hasSameSort) {
                $otherArticles = Article::where('sort', '>=', $data['sort'])->get();

                foreach ($otherArticles as $otherArticle) {
                    $otherArticle->sort = $otherArticle->sort + 1;
                    $otherArticle->save();
                }
            }
            Article::create($data);

            return response()->ajaxSuccess('添加成功');
        } catch (Exception $e) {
            return response()->ajaxFail('添加失败');
        }
    }

    /**
     * 帮助修改视图
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function helpEdit(Request $request, $id)
    {
        $categoryId = $request->category_id;

        $article = Article::find($id);

        return view('back.article.help.edit', compact('categoryId', 'article'));
    }

    /**
     * 帮助修改
     * @param Request $request
     * @return mixed
     */
    public function helpUpdate(Request $request)
    {
        try {
            $article = Article::find($request->id);
            $data = $request->data;
            // 排序
            $hasSameSort = Article::where('sort', $data['sort'])->first();
            if ($hasSameSort) {
                $otherArticles = Article::where('sort', '>=', $data['sort'])->get();

                foreach ($otherArticles as $otherArticle) {
                    $otherArticle->sort = $otherArticle->sort + 1;
                    $otherArticle->save();
                }
            }
            $article->update($data);

            return response()->ajaxSuccess('修改成功');
        } catch (Exception $e) {
            return response()->ajaxFail('修改失败');
        }
    }

    /**
     * 帮助删除
     * @param Request $request
     * @return mixed
     */
    public function helpDelete(Request $request)
    {
        try {
            $article = Article::find($request->id);
            $article->delete();

            return response()->ajaxSuccess('删除成功');
        } catch (Exception $e) {
            return response()->ajaxFail('删除失败');
        }
    }

    /**
     * 公告分类列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categoryNoticeIndex(Request $request)
    {
        $categories = ArticleCategory::where('parent_id', 1)->oldest('sort')->paginate(10);

        if ($request->ajax()) {
            $categories = ArticleCategory::where('parent_id', 1)->oldest('sort')->paginate(10);

            return response()->json(view('back.article.notice.category.list', compact('categories'))->render());
        };

        return view('back.article.notice.category.index', compact('categories'));
    }

    /**
     * 公告分类添加视图
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categoryNoticeCreate(Request $request)
    {
        $parents = ArticleCategory::where('parent_id', 1)->get();

        return view('back.article.notice.category.create', compact('parents'));
    }

    /**
     * 公告分类添加
     * @param Request $request
     * @return mixed
     */
    public function categoryNoticeStore(Request $request)
    {
        try {
            $data = $request->data;
            // 排序
            $hasSameSort = ArticleCategory::where('sort', $data['sort'])->first();
            if ($hasSameSort) {
                $otherArticleCategories = ArticleCategory::where('sort', '>=', $data['sort'])->get();

                foreach ($otherArticleCategories as $otherArticleCategory) {
                    $otherArticleCategory->sort = $otherArticleCategory->sort + 1;
                    $otherArticleCategory->save();
                }
            }
            ArticleCategory::create($data);

            return response()->ajaxSuccess('添加成功');
        } catch (Exception $e) {
            return response()->ajaxFail('添加失败');
        }
    }

    /**
     * 公告分类修改视图
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categoryNoticeEdit(Request $request, $id)
    {
        $category = ArticleCategory::find($id); // 分类

        $parents = ArticleCategory::where('parent_id', 1)->get();

        return view('back.article.notice.category.edit', compact('category', 'parents'));
    }

    /**
     * 公告分类修改
     * @param Request $request
     * @return mixed
     */
    public function categoryNoticeUpdate(Request $request)
    {
        try {
            $article = ArticleCategory::find($request->id);
            $data = $request->data;
            // 排序
            $hasSameSort = ArticleCategory::where('sort', $data['sort'])->first();
            if ($hasSameSort) {
                $otherArticleCategories = ArticleCategory::where('sort', '>=', $data['sort'])->get();

                foreach ($otherArticleCategories as $otherArticleCategory) {
                    $otherArticleCategory->sort = $otherArticleCategory->sort + 1;
                    $otherArticleCategory->save();
                }
            }
            $article->update($data);

            return response()->ajaxSuccess('修改成功');
        } catch (Exception $e) {
            return response()->ajaxFail('修改失败');
        }
    }

    /**
     * 公告分类删除
     * @param Request $request
     * @return mixed
     */
    public function categoryNoticeDelete(Request $request)
    {
        DB::beginTransaction();
        try {
        $articleCategory = ArticleCategory::find($request->id);
            $articleCategory->articles()->delete();
            $articleCategory->delete();
        } catch (Exception $e) {
            DB::rollback();
            return response()->ajaxFail('删除失败');
        }
        DB::commit();
        return response()->ajaxSuccess('删除成功');
    }


    /**
     * 帮助分类列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categoryHelpIndex(Request $request)
    {
        $categories = ArticleCategory::where('parent_id', 2)->oldest('sort')->paginate(10);

        if ($request->ajax()) {
            $categories = ArticleCategory::where('parent_id', 2)->oldest('sort')->paginate(10);

            return response()->json(view('back.article.help.category.list', compact('categories'))->render());
        };

        return view('back.article.help.category.index', compact('categories'));
    }

    /**
     * 帮助分类添加视图
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categoryHelpCreate(Request $request)
    {
        $parents = ArticleCategory::where('parent_id', 2)->get();

        return view('back.article.help.category.create', compact('parents'));
    }

    /**
     * 帮助分类添加
     * @param Request $request
     * @return mixed
     */
    public function categoryHelpStore(Request $request)
    {
        try {
            $data = $request->data;
            // 排序
            $hasSameSort = ArticleCategory::where('sort', $data['sort'])->first();
            if ($hasSameSort) {
                $otherArticleCategories = ArticleCategory::where('sort', '>=', $data['sort'])->get();

                foreach ($otherArticleCategories as $otherArticleCategory) {
                    $otherArticleCategory->sort = $otherArticleCategory->sort + 1;
                    $otherArticleCategory->save();
                }
            }
            ArticleCategory::create($data);

            return response()->ajaxSuccess('添加成功');
        } catch (Exception $e) {
            return response()->ajaxFail('添加失败');
        }
    }

    /**
     * 帮助分类修改视图
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categoryHelpEdit(Request $request, $id)
    {
        $category = ArticleCategory::find($id); // 分类

        $parents = ArticleCategory::where('parent_id', 2)->get();

        return view('back.article.help.category.edit', compact('category', 'parents'));
    }

    /**
     * 帮助分类修改
     * @param Request $request
     * @return mixed
     */
    public function categoryHelpUpdate(Request $request)
    {
        try {
            $article = ArticleCategory::find($request->id);
            $data = $request->data;
            // 排序
            $hasSameSort = ArticleCategory::where('sort', $data['sort'])->first();
            if ($hasSameSort) {
                $otherArticleCategories = ArticleCategory::where('sort', '>=', $data['sort'])->get();

                foreach ($otherArticleCategories as $otherArticleCategory) {
                    $otherArticleCategory->sort = $otherArticleCategory->sort + 1;
                    $otherArticleCategory->save();
                }
            }
            $article->update($data);

            return response()->ajaxSuccess('修改成功');
        } catch (Exception $e) {
            return response()->ajaxFail('修改失败');
        }
    }

    /**
     * 帮助分类删除
     * @param Request $request
     * @return mixed
     */
    public function categoryHelpDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $articleCategory = ArticleCategory::find($request->id);
            $articleCategory->articles()->delete();
            $articleCategory->delete();
        } catch (Exception $e) {
            DB::rollback();
            return response()->ajaxFail('删除失败');
        }
        DB::commit();
        return response()->ajaxSuccess('删除成功');
    }
}
