<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * @param $query
     * @param $condition
     * @return mixed
     */
    public function scopeCondition($query, $condition)
    {
        if (isset($condition['name']) && $condition['name']) {
            $query->where('name', 'like', '%' . $condition['name'] . '%');
        }
        return $query;
    }

    /**
     * 获取角色并 缓存
     * @return mixed
     * @throws \Exception
     */
    public function cachedRoles()
    {
        return cache()->tags(self::getTable())->rememberForever($this->id, function () {
            return $this->roles()->get();
        });
    }

    /**
     * 保存时清除缓存
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);
        cache()->tags(self::getTable())->flush();
        return $result;
    }

    /**
     * 删除时清除缓存
     * @param array $options
     * @return bool|null
     * @throws \Exception
     */
    public function delete(array $options = [])
    {
        $result = parent::delete($options);
        cache()->tags(self::getTable())->flush();
        return $result;
    }

    /**
     * 用户与角色对应关系
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class);
    }

    /**
     * 检测用户是否拥有角色
     * @param $name
     * @param bool $requireAll
     * @return bool
     * @throws \Exception
     */
    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 检测用户是户拥有权限
     * @param string|array $permission 传入权限单个或数组
     * @param bool $requireAll  如果第一个参数为数组,则依靠此参数检测是否需要数组中所有路由有权限
     * @return bool
     * @throws \Exception
     */
    public function can($permission, $requireAll = false)
    {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);

                if ($hasPerm && !$requireAll) { // 第二个值为false  数组中的路由任意一个值有权限都返回true
                    return true;
                } elseif (!$hasPerm && $requireAll) { //  第二个值为true, 数组中的路由任意一个没有权限都返回false
                    return false;
                }
            }
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                foreach ($role->cachedPermissions() as $perm) {
                    if (str_is($permission, $perm->route_name)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Save the inputted permissions.
     *
     * @param mixed $inputRoles
     *
     * @return void
     */
    public function saveRoles($inputRoles)
    {
        if (!empty($inputRoles)) {
            $this->roles()->sync($inputRoles);
        } else {
            $this->roles()->detach();
        }
    }

    // 屏蔽remember_token 字段
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute)
        {
            parent::setAttribute($key, $value);
        }
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if (! empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}
