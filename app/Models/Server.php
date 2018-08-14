<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    public $fillable = [
      'name',
      'region_id',
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
        if (isset($condition['game_type_id']) && $condition['game_type_id']) {
            $query->where('game_type_id', $condition['game_type_id']);
        }
        if (isset($condition['game_class_id']) && $condition['game_class_id']) {
            $query->where('game_class_id', $condition['game_class_id']);
        }

        if (isset($condition['region_id']) && $condition['region_id']) {
            $query->where('region_id', $condition['region_id']);
        }
        return $query;
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
