<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait HasPermissions
{
    /**
     * Get all permission
     * Including user and user's role
     *
     * @return Collection
     */
    public function allPermissions()
    {
        return $this
            ->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->merge($this->permissions)
            ->unique('id')
            ->values();
    }

    public function allPermissionSlug()
    {
        return $this
            ->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->merge($this->permissions)
            ->unique('id')
            ->pluck('slug');
    }

    /**
     * @param $ability
     * @return bool
     */
    public function can($ability, $arguments = [])
    {
        if ($this->isAdministrator()) {
            return true;
        }

        if ($this->permissions->pluck('slug')->contains($ability)) {
            return true;
        }

        return $this->roles->pluck('permissions')->flatten()->pluck('slug')->contains($ability);
    }

    /**
     * @param $ability
     * @return bool
     */
    public function cannot($ability, $arguments = [])
    {
        return !$this->can($ability);
    }

    /**
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->isRole('administrator');
    }

    /**
     * @param string $role
     * @return bool
     */
    public function isRole(string $role)
    {
        return $this->roles->pluck('slug')->contains($role);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function inRoles($roles = [])
    {
        return $this->roles->pluck('slug')->intersect($roles)->isNotEmpty();
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function visible($roles = [])
    {
        if (empty($roles)) {
            return true;
        }

        $roles = array_column($roles, 'slug');

        return $this->isAdministrator() || $this->inRoles($roles);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->roles()->detach();
            $model->permissions()->detach();
        });
    }
}
