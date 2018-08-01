<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockadeAccount extends Model
{
    protected $fillable = ['user_id', 'type', 'start_time', 'end_time', 'reason', 'remark', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 后台封号查找
     * @param $query
     * @param array $filters
     * @return mixed
     */
    public static function scopeFilter($query, $filters = [])
    {
        if (isset($filters['nameOrId'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('name', $filters['nameOrId'])->orWhere('id', $filters['nameOrId']);
            });
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['startTime'])) {
            $query->where('start_time', '>=', $filters['startTime']);
        }

        if (isset($filters['endTime'])) {
            $query->where('end_time', '<=', $filters['endTime']);
        }

        return $query;
    }
}
