<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 游戏分类 如:射击
 * Class GameClass
 *
 * @package App\Models
 */
class GameClass extends Model
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
