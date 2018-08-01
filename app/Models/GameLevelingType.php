<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameLevelingType extends Model
{
    public  $fillable = [
      'name',
      'game_id',

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
        return $query;
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
