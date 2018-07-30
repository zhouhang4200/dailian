<?php

namespace App\Http\Controllers\Back;

use App\Models\Game;
use App\Http\Controllers\Controller;
use App\Models\GameClass;
use App\Models\GameType;

/**
 * Class GameController
 * @package App\Http\Controllers
 */
class GameController extends Controller
{
    /**
     * 游戏列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.game.index', [
            'games' => Game::condition(request()->all())->paginate()
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.game.create', [
            'gameTypes' => GameType::getAll(),
            'gameClasses' => GameClass::getAll(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            Game::create([
                'name' => request('name'),
                'icon' => request('icon'),
                'initials' => request('icon'),
                'game_type_id' => request('game_type_id'),
                'game_class_id' => request('game_class_id'),
            ]);
            return redirect(route('admin.game.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.game.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('back.game.edit', [
            'game' => Game::findOrFail(request('id')),
            'gameTypes' => GameType::getAll(),
            'gameClasses' => GameClass::getAll(),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        try {
            $game = Game::find($id);
            $game->name = request('name');
            $game->icon = request('icon');
            $game->initials = request('initials');
            $game->game_type_id = request('game_type_id');
            $game->game_class_id = request('game_class_id');
            $game->save();

            return redirect(route('admin.game.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.game.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        Game::destroy(request('id'));
        return response()->ajaxSuccess('删除成功');
    }
}
