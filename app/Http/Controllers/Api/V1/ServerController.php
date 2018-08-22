<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Server;
use App\Http\Controllers\Controller;

/**
 * Class ServerController
 * @package App\Http\Controllers\Api\V1
 */
class ServerController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        if (! request('region_id')){
            return response()->apiJson(1001);
        }

        return response()->apiJson(0, Server::select('region_id', 'id', 'name')
            ->where('region_id', request('region_id'))->get());
    }
}
