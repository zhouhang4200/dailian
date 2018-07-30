<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $fillable = [
        'name',
        'game_id',
        'initials',
    ];

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
        if (isset($condition['game_id']) && $condition['game_id']) {
            $query->where('game_id', $condition['game_id']);
        }

        return $query;
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }
}
