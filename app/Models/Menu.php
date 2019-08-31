<?php

namespace App\Models;

use App\Traits\ModelTree;
use App\Utils\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Menu extends Model
{
    use ModelTree {
        ModelTree::allNodesQuery as parentAllNodesQuery;
    }

    protected $table = 'admin_menu';

    protected $fillable = [
        'parent_id', 'order', 'name', 'title', 'component', 'icon', 'path', 'is_cache', 'is_hidden', 'permission',
    ];

    protected $treeWithAuth = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_role_menu', 'menu_id', 'role_id')->withTimestamps();
    }

    /**
     * @return $this
     */
    public function treeWithAuth()
    {
        $this->treeWithAuth = true;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function allNodesQuery()
    {
        return $this->parentAllNodesQuery()
            ->when($this->treeWithAuth, function (Builder $query) {
                $query->with('roles');
            });
    }

    protected function ignoreTreeNode($node): bool
    {
        if (!$this->treeWithAuth) {
            return false;
        }

        if (
            Admin::user()->visible($node['roles']) &&
            (empty($node['permission']) ?: Admin::user()->can($node['permission']))
        ) {
            return false;
        }

        return true;
    }
}
