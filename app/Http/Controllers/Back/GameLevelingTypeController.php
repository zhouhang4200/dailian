<?php

namespace App\Http\Controllers\Back;

use App\Models\Game;
use App\Models\GameLevelingType;
use App\Http\Controllers\Controller;

class GameLevelingTypeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.game-leveling-type.index', [
            'gameLevelingTypes' => GameLevelingType::condition(request()->all())->paginate(),
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.game-leveling-type.create', [
            'games' => Game::getAll(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            $typeNameArr = explode(',', request('name'));

            $types = [];
            foreach ($typeNameArr as $item) {
                $name = trim($item);
                $types[] = [
                    'name' => $name,
                    'game_id' => request('game_id'),
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                ];
            }

            GameLevelingType::insert($types);
            return redirect(route('admin.game-leveling-type.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.game-leveling-type.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('back.game-leveling-type.edit', [
            'games' => Game::getAll(),
            'gameLevelingType' => GameLevelingType::find($id),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        try {
            $game = GameLevelingType::find($id);
            $game->name = request('name');
            $game->game_id = request('game_id');
            $game->save();

            return redirect(route('admin.game-leveling-type.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.game-leveling-type.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // 查找是否有对应的服务器
        GameLevelingType::destroy($id);
        return response()->ajaxSuccess('删除成功');
    }
}
