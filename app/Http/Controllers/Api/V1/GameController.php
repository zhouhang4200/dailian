<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Game;
use App\Http\Controllers\Controller;

/**
 * Class GameController
 * @package App\Http\Controllers\Api\V1
 */
class GameController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return response()->apiJson(0, Game::select('id', 'name')->get());
    }
}
