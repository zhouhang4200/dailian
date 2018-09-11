<?php

namespace App\Http\Controllers\Back;

use App\Models\Game;
use App\Http\Controllers\Controller;
use App\Models\GameClass;
use App\Models\GameType;
use Illuminate\Support\Facades\Validator;

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

            if (! request()->hasFile('icon')) {
                request()->flash();
                return redirect(route('admin.game.create'))->with('fail', '请传入游戏图标');
            }

            $path = '';
            if (request()->file('icon')) {
                $img = request()->file('icon');
                $path = '/storage/' . $img->store(date('Ymd'), 'public');
            }

            Game::create([
                'name' => request('name'),
                'icon' => $path,
                'initials' => getFirstChar(request('name')) == null ? substr(request('name'), 0 ,1) : getFirstChar(request('name')),
                'game_type_id' => request('game_type_id'),
                'game_class_id' => request('game_class_id'),
            ]);
            return redirect(route('admin.game.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            request()->flash();
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
            $updateData = [
                'name' => request('name'),
                'initials' => getFirstChar(request('name')) == null ? substr(request('name'), 0 ,1) : getFirstChar(request('name')),
                'game_type_id' =>  request('game_type_id'),
                'game_class_id' =>  request('game_class_id'),
            ];

            if (request()->file('icon')) {
                $updateData['icon'] = '/storage/' .  request()->file('icon')->store('game', 'public');
            }

            Game::where('id' , $id)->update($updateData);

            return redirect(route('admin.game.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            request()->flash();
            return redirect(route('admin.game.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function delete($id)
    {
        Game::destroy($id);
        return response()->ajaxSuccess('删除成功');
    }
}
