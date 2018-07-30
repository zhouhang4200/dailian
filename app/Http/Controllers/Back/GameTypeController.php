<?php

namespace App\Http\Controllers\Back;

use App\Models\Game;
use App\Models\GameClass;
use App\Models\GameType;
use App\Http\Controllers\Controller;

/**
 * Class GameTypeController
 * @package App\Http\Controllers\Back
 */
class GameTypeController extends Controller
{
    /**
     * 列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.game-type.index', [
            'gameTypes' => GameType::condition(request()->all())->paginate()
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.game-type.create');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            GameType::create([
                'name' => request('name'),
            ]);
            return redirect(route('admin.game-type.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.game-type.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('back.game-type.edit', [
            'gameType' => GameType::findOrFail(request('id')),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        try {
            $gameType = GameType::find($id);
            $gameType->name = request('name');
            $gameType->save();

            return redirect(route('admin.game-type.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.game-type.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }

    /**
     * 删除类型
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete()
    {
        $inUse = Game::where('game_type_id', request('id'))->first();
        if ($inUse) {
            return response()->ajaxFail('该类型有关联的游戏,不能删除');
        }

        GameType::destroy(request('id'));
        return response()->ajaxSuccess('删除成功');
    }
}
