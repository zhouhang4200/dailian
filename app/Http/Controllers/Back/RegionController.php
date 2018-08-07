<?php

namespace App\Http\Controllers\Back;

use App\Models\Game;
use App\Models\Region;
use App\Http\Controllers\Controller;

/**
 * Class RegionController
 * @package App\Http\Controllers\Back
 */
class RegionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.region.index', [
            'regions' => Region::condition(request()->all())->paginate(),
        ]);
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.region.create', [
            'games' => Game::getAll(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {
            $regionsIds = explode(',', request('name'));

            $regions = [];
            foreach ($regionsIds as $item) {
                $name = trim($item);
                $regions[] = [
                    'name' => $name,
                    'initials' => getFirstChar($name) ?? substr($name, 0, 1),
                    'game_id' => request('game_id'),
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                ];
            }

            Region::insert($regions);
            return redirect(route('admin.region.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            request()->flash();
            return redirect(route('admin.region.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('back.region.edit', [
            'games' => Game::getAll(),
            'region' => Region::find($id),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        try {
            $game = Region::find($id);
            $game->name = request('name');
            $game->initials = request('name');
            $game->game_id = request('game_id');
            $game->save();

            return redirect(route('admin.region.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.region.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // 查找是否有对应的服务器
        Region::destroy($id);
        return response()->ajaxSuccess('删除成功');
    }

    /**
     * 根据传入的游戏ID获取区
     * @return mixed
     */
    public function getRegionByGameId()
    {
        $regions = Region::where('game_id', request('id'))->pluck('name', 'id');

        return response()->ajaxSuccess('获取成功', $regions);
    }

}
