<?php

namespace App\Utils;

use Illuminate\Support\Arr;

class CommonUtils
{
    public static function menuToVue(array $menuTree)
    {
        $menu = [];
        if (empty($menuTree)) {
            return $menu;
        }

        foreach ($menuTree as $item) {
            $tmp = [
                'id' => $item['id'],
                'name' => $item['name'],
                'path' => $item['path'],
                'component' => $item['component'],
                'hidden' => (bool)$item['is_hidden'],
                'meta' => [
                    'title' => $item['title'] ?? '',
                    'icon' => $item['icon'] ?? '',
                    'noCache' => (bool)$item['is_cache']
                ]
            ];
            if (!empty($item['roles'])) {
                $tmp['meta']['roles'] = Arr::pluck($item['roles'], 'slug');
            }
            if (!empty($item['children'])) {
                $tmp['children'] = self::menuToVue($item['children']);
            }
            array_push($menu, $tmp);
        }

        return $menu;
    }
}
