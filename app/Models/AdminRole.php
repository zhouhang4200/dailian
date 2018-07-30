<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminRole
 * @package App\Models
 */
class AdminRole extends Model
{
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($role) {
            $role->adminUsers()->sync([]);
            $role->permissions()->sync([]);
            return true;
        });
    }

    /**
     * @var array
     */
    public $fillable = ['name'];

    /**
     * @return mixed
     * @throws \Exception
     */
    public function cachedPermissions()
    {
        return cache()->tags(self::getTable())->rememberForever($this->id, function () {
            return $this->permissions()->get();
        });
    }

    /**
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
    public function adminUsers()
    {
        return $this->belongsToMany(AdminUser::class,'admin_users','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(){
        return $this->belongsToMany(AdminPermission::class);
    }

    /**
     * @param $name
     * @param bool $requireAll
     * @return bool
     * @throws \Exception
     */
    public function hasPermission($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);

                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        } else {
            foreach ($this->cachedPermissions() as $permission) {
                if ($permission->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Save the inputted permissions.
     *
     * @param mixed $inputPermissions
     *
     * @return void
     */
    public function savePermissions($inputPermissions)
    {
        if (!empty($inputPermissions)) {
            $this->permissions()->sync($inputPermissions);
        } else {
            $this->permissions()->detach();
        }
    }
}
