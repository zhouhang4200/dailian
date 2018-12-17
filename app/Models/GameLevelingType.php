<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameLevelingType extends Model
{
    public  $fillable = [
      'name',
      'game_id',
      'poundage',
    ];

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
        if (isset($condition['game_id']) && $condition['game_id']) {
            $query->where('game_id', $condition['game_id']);
        }
        return $query;
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
