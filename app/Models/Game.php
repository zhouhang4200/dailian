<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 游戏
 * Class Game
 *
 * @package App\Models
 */
class Game extends Model
{
    public $fillable = [
        'name',
        'sort',
        'icon',
        'initials',
        'game_type_id',
        'game_class_id',
    ];

    /**
     * 获取所有游戏
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAll()
    {
        return self::orderBy('sort')->get();
    }

    /**
     * @param $query
     * @param $condition
     * @return mixed
     */
    public static function scopeCondition($query, $condition)
    {
        if (isset($condition['id']) && $condition['id']) {
            $query->where('id', $condition['id']);
        }
        if (isset($condition['name']) && $condition['name']) {
            $query->where('name', 'like', '%' . $condition['name'] . '%');
        }
        if (isset($condition['game_type_id']) && $condition['game_type_id']) {
            $query->where('game_type_id', $condition['game_type_id']);
        }
        if (isset($condition['game_class_id']) && $condition['game_class_id']) {
            $query->where('game_class_id', $condition['game_class_id']);
        }
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameClass()
    {
        return $this->belongsTo(GameClass::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameType()
    {
        return $this->belongsTo(GameType::class);
    }

    /**
     * @param $key
     * @return string
     */
    public function getIconAttribute($key)
    {
        if ($this->attributes['icon']) {
            return asset($this->attributes['icon']);
        }
        return $this->attributes['icon'];
    }
}
