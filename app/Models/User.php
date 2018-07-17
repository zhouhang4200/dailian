<?php

namespace App\Models;

use Auth;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

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
        'phone', 'password', 'pay_password', 'name', 'parent_id', 'avatar', 'email'
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
    public function roles() {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions() {
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
}
