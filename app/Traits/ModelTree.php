<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait ModelTree
{
    /**
     * @var array
     */
    protected static $branchOrder = [];

    /**
     * @var string
     */
    protected $parentColumn = 'parent_id';

    /**
     * @var string
     */
    protected $titleColumn = 'title';

    /**
     * @var string
     */
    protected $orderColumn = 'order';

    /**
     * @var
     */
    protected $queryCallback;

    /**
     * Get children of current node.
     */
    public function children()
    {
        return $this->hasMany(static::class, $this->parentColumn);
    }

    /**
     * Get parent of current node.
     */
    public function parent()
    {
        return $this->belongsTo(static::class, $this->parentColumn);
    }

    /**
     * @return string
     */
    public function getParentColumn(): string
    {
        return $this->parentColumn;
    }

    /**
     * @param string $parentColumn
     */
    public function setParentColumn(string $parentColumn): void
    {
        $this->parentColumn = $parentColumn;
    }

    /**
     * @return string
     */
    public function getTitleColumn(): string
    {
        return $this->titleColumn;
    }

    /**
     * @param string $titleColumn
     */
    public function setTitleColumn(string $titleColumn): void
    {
        $this->titleColumn = $titleColumn;
    }

    /**
     * @return string
     */
    public function getOrderColumn(): string
    {
        return $this->orderColumn;
    }

    /**
     * @param string $orderColumn
     */
    public function setOrderColumn(string $orderColumn): void
    {
        $this->orderColumn = $orderColumn;
    }

    /**
     * @param Closure|null $query
     * @return $this
     */
    public function withQuery(\Closure $query = null)
    {
        $this->queryCallback = $query;

        return $this;
    }


    /**
     * @return array|mixed
     */
    public function toTree()
    {
        return $this->buildNestedArray();
    }

    /**
     * @param array $nodes
     * @param int $parentId
     * @return array|mixed
     */
    public function buildNestedArray(array $nodes = [], $parentId = 0)
    {
        $branch = [];

        if (empty($nodes)) {
            return $this->allNodes();
        }

        foreach ($nodes as $node) {
            if ($node[$this->parentColumn] == $parentId) {
                $children = $this->buildNestedArray($nodes, $node[$this->getKeyName()]);
                $node['children'] = $children;
                $branch[] = $node;
            }
        }
        return $branch;
    }

    /**
     * Get all elements.
     *
     * @return mixed
     */
    public function allNodes()
    {
        //        $this->getAllNodes();
        $this->allNodesQuery()->get()->array();
    }

    protected function allNodesQuery()
    {
        return static::query()->orderBy($this->orderColumn);
    }

    protected function getAllNodes()
    {
        $orderColumn = DB::getQueryGrammar()->wrap($this->orderColumn);
        $byOrder = $orderColumn . ' = 0,' . $orderColumn;
        $self = new static();
        if ($this->queryCallback instanceof \Closure) {
            $self = call_user_func($this->queryCallback, $self);
        }

        return $self->orderByRaw($byOrder)->get()->toArray();
    }

    /**
     * Set the order of branches in the tree.
     *
     * @param array $order
     *
     * @return void
     */
    protected static function setBranchOrder(array $order)
    {
        static::$branchOrder = array_flip(Arr::flatten($order));
        static::$branchOrder = array_map(function ($item) {
            return ++$item;
        }, static::$branchOrder);
    }

    /**
     * Save tree order from a tree like array.
     *
     * @param array $tree
     * @param int $parentId
     */
    public static function saveOrder($tree = [], $parentId = 0)
    {
        if (empty(static::$branchOrder)) {
            static::setBranchOrder($tree);
        }
        foreach ($tree as $branch) {
            $node = static::find($branch['id']);
            $node->{$node->getParentColumn()} = $parentId;
            $node->{$node->getOrderColumn()} = static::$branchOrder[$branch['id']];
            $node->save();
            if (isset($branch['children'])) {
                static::saveOrder($branch['children'], $branch['id']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $this->where($this->parentColumn, $this->getKey())->delete();
        return parent::delete();
    }
}
