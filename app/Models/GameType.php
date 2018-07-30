<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 游戏类型 手游 端游
 * Class GameType
 *
 * @package App\Models
 */
class GameType extends Model
{
    public $fillable = ['name'];

    /**
     * @return GameType[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAll()
    {
        return self::all();
    }

    /**
     * @param $query
     * @param $condition
     * @return mixed
     */
    public static function scopeCondition($query, $condition)
    {
        if (isset($condition['name']) && $condition['name']) {
            $query->where('name', 'like', '%' .$condition['name'] . '%');
        }
        return $query;
    }
}
