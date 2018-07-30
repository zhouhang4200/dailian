<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminPermission
 * @package App\Models
 */
class AdminPermission extends Model
{
    /**
     * @var array
     */
    public $fillable = [
        'name',
        'route_name',
        'admin_permission_group_id',
    ];

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


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne(AdminPermissionGroup::class, 'id', 'admin_permission_group_id');
    }

    /**
     * 删除权限时移除对应角色的权限
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($permission) {
            $permission->roles()->sync([]);
            return true;
        });
    }
}
