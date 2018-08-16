<?php

namespace App\Http\Controllers\Front;

use App\Models\Game;
use App\Models\GameLevelingOrder;
use App\Http\Controllers\Controller;
use App\Models\GameLevelingType;
use App\Models\Region;
use App\Models\Server;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Front
 */
class OrderController extends Controller
{
    /**
     * 首页待接单列表
     */
    public function index()
    {
        $regions = [];
        $servers = [];
        $gameLevelingTypes = [];

        if (request('game_id')) {
            $regions = Region::condition(['game_id' => request('game_id')])->get(['name', 'id']);
            $gameLevelingTypes = GameLevelingType::where('game_id', request('game_id'))->get(['name', 'id']);
        }

        if (request('region_id')) {
            $servers = Server::condition(['region_id' => request('region_id')])->get(['name', 'id']);
        }

        return view('front.order.index', [
            'orders' => GameLevelingOrder::condition(array_merge(request()->except('status'), ['status' => 1]))->paginate(20),
            'guest' => auth()->guard()->guest(),
            'games' => Game::all(),
            'regions' => $regions,
            'servers' => $servers,
            'gameLevelingTypes' => $gameLevelingTypes,
        ]);
    }

    /**
     * 待接单列表
     */
    public function wait()
    {
        $regions = [];
        $servers = [];
        $gameLevelingTypes = [];

        if (request('game_id')) {
            $regions = Region::condition(['game_id' => request('game_id')])->get(['name', 'id']);
            $gameLevelingTypes = GameLevelingType::where('game_id', request('game_id'))->get(['name', 'id']);
        }

        if (request('region_id')) {
            $servers = Server::condition(['region_id' => request('region_id')])->get(['name', 'id']);
        }

        return view('front.order.wait', [
            'orders' => GameLevelingOrder::condition(array_merge(request()->except('status'), ['status' => 1]))->paginate(20),
            'guest' => auth()->guard()->guest(),
            'games' => Game::all(),
            'regions' => $regions,
            'servers' => $servers,
            'gameLevelingTypes' => $gameLevelingTypes,
        ]);
    }

    /**
     * 获取游戏服务器
     */
    public function getServers()
    {
        if (request()->ajax()) {
            return response()->ajaxSuccess('获取成功', Server::condition(['region_id' => request('region_id')])->get(['id', 'name']));
        }
    }
}
