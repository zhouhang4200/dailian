<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * 获取所有游戏
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAll()
    {
        return self::all();
    }

}
