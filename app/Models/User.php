<?php

namespace App\Models;

use Auth;
use Cache;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'password', 'name', 'parent_id', 'avatar', 'email', 'qq', 'wechat', 'status', 'signature'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 获取父ID
     * @return mixed
     */
    public function getParentIdAttribute()
    {
        if ($this->attributes['parent_id'] == 0) {
            return $this->attributes['id'];
        }
        return $this->attributes['parent_id'];
    }
    /**
     * 获取父ID
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        if ($this->attributes['avatar']) {
            return asset($this->attributes['avatar']);
        }
        return $this->attributes['avatar'];
    }

    /**
     * 是否是主账号
     * @return bool
     */
    public function isParent()
    {
        if ($this->attributes['parent_id'] == 0) {
            return true;
        }
        return false;
    }

    public function realNameCertification()
    {
        return $this->hasOne(RealNameCertification::class, 'user_id');
    }

    public function userAsset()
    {
        return $this->hasOne(UserAsset::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    /**
     * @param $query
     * @param array $filters
     * @return mixed
     */
    public static function scopeEmployeeFilter($query, $filters = [])
    {
        if ($filters['userId']) {
            $query->where('id', $filters['userId']);
        }

        if ($filters['name']) {
            $query->where('name', $filters['name']);
        }

        if ($filters['station']) {
            $userIds = Role::find($filters['station'])->users->pluck('id');
            $query->whereIn('id', $userIds);
        }
        return $query->where('parent_id', Auth::user()->parent_id);
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * 后台商户列表查找
     * @param $query
     * @param array $filters
     * @return mixed
     */
    public static function scopeFilter($query, $filters = [])
    {
        if ($filters['id']) {
            $query->where('id', $filters['id']);
        }

        if ($filters['name']) {
            $query->where('name', $filters['name']);
        }

        if ($filters['phone']) {
            $query->where('phone', $filters['phone']);
        }

        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blockadeAccounts()
    {
        return $this->hasMany(BlockadeAccount::class);
    }

    /**
     *  判断当前登录人是否有权限
     * @param $permission
     * @return bool|mixed
     */
    public function could($permission)
    {
        $userHasPermissions = $this->getUserPermissions() ? $this->getUserPermissions()->pluck('name')->toArray() : [];

        // 如果是数组
        if (is_array($permission)) {
            foreach ($permission as $value) {
                // 如果有权限,判断当前页面权限是否在登录人权限中
                if (in_array($value, $userHasPermissions)) {
                    return $value;
                }
            }
        } else {
            // 如果有权限,判断当前页面权限是否在登录人权限中
            if (in_array($permission, $userHasPermissions)) {
                return $permission;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getUserPermissions()
    {
        $key = 'permission:user:'.$this->id;

        return Cache::rememberForever($key, function () {
            return $this->load('roles', 'roles.permissions')
                    ->roles->flatMap(function ($role) {
                        return $role->permissions;
                    })->sort()->values();
        });
    }


}
