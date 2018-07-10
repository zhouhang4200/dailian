<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'password', 'pay_password',
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

    }
}
