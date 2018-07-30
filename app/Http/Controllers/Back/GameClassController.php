<?php

namespace App\Http\Controllers\Back;

use App\Models\Game;
use App\Models\GameClass;
use App\Models\GameType;
use App\Http\Controllers\Controller;

/**
 * Class GameClassController
 * @package App\Http\Controllers\Back
 */
class GameClassController extends Controller
{
    /**
     * 列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.game-class.index', [
            'gameClasses' => GameClass::condition(request()->all())->paginate()
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.game-class.create');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            GameClass::create([
                'name' => request('name'),
            ]);
            return redirect(route('admin.game-class.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.game-class.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('back.game-class.edit', [
            'gameClass' => GameClass::findOrFail(request('id')),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        try {
            $gameClass = GameClass::find($id);
            $gameClass->name = request('name');
            $gameClass->save();

            return redirect(route('admin.game-class.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.game-class.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }

    /**
     * 删除分类
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete()
    {
        $inUse = Game::where('game_class_id', request('id'))->first();
        if ($inUse) {
            return response()->ajaxFail('该分类有关联的游戏,不能删除');
        }

        GameClass::destroy(request('id'));
        return response()->ajaxSuccess('删除成功');
    }
}
