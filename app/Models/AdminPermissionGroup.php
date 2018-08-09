<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminPermissionGroup extends Model
{
    public $fillable = ['name'];

    /**
     * @param $query
     * @param $condition
     * @return mixed
     */
    public function scopeCondition($query, $condition)
    {
        if (isset($condition['name']) && $condition['name']) {
            $query->where('name', 'lick', '%' . $condition['name'] . '%');
        }
        if (isset($condition['username']) && $condition['username']) {
            $query->where('username', 'lick', '%' . $condition['username'] . '%');
        }

        return $query;
    }

}
