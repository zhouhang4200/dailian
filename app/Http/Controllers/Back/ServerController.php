<?php

namespace App\Http\Controllers\Back;

use App\Models\Game;
use App\Models\Region;
use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('back.server.index', [
            'servers' => Server::condition(request()->all())->paginate(),
        ]);
    }


    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('back.server.create', [
            'games' => Game::getAll(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        try {

            $serverNameArr = explode(',', request('name'));

            $servers = [];
            foreach ($serverNameArr as $item) {
                $name = trim($item);
                $servers[] = [
                    'name' => $name,
                    'initials' => getFirstChar($name) ?? substr($name, 0, 1),
                    'region_id' => request('region_id'),
                    'created_at' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d'),
                ];
            }
            Server::insert($servers);
            return redirect(route('admin.server.create'))->with('success', '添加成功');
        } catch (\Exception $exception) {
            request()->flash();
            return redirect(route('admin.server.create'))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $server = Server::find($id);
        return view('back.server.edit', [
            'server' => $server,
            'games' => Game::getAll(),
            'regions' => Region::where('id', $server->region_id)->get(),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        try {
            $game = Server::find($id);
            $game->name = request('name');
            $game->initials = request('name');
            $game->region_id = request('region_id');
            $game->save();

            return redirect(route('admin.server.update', ['id' => $id]))->with('success', '更新成功');
        } catch (\Exception $exception) {
            return redirect(route('admin.server.update', ['id' => $id]))->with('fail', $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // 查找是否有对应的服务器
        Server::destroy($id);
        return response()->ajaxSuccess('删除成功');
    }
}
