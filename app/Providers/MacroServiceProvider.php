<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class MacroServiceProvider extends ServiceProvider
{
    private function collectionMacro()
    {
        Collection::macro('keyByRecursive', function ($func) {

            return $this->keyBy(function ($value, $key) use ($func) {

                return $func($value, $key);

            })->map(function ($item) use ($func) {

                if ($item instanceof Collection) {

                    return $item->keyByRecursive($func);
                }
                if (is_array($item) || is_object($item)) {

                    return collect($item)->keyByRecursive($func);
                }
                return $item;
            });
        });

        Collection::macro('camelCaseKeys', function () {
            return $this->keyByRecursive(function ($value, $key) {
                return Str::camel($key);
            });
        });

        Collection::macro('snakeCaseKeys', function () {
            return $this->keyByRecursive(function ($value, $key) {
                return Str::snake($key);
            });
        });

        Collection::macro('deepForget', function ($fields) {
            return $this->map(function($item) use ($fields) {
                if (is_array($item) || is_object($item)) {
                    $shallowFields = [];
                    $deepFields = [];
                    foreach ($fields as $field) {
                        if (strpos($field, '.*.')) {
                            $keys = explode('.*.', $field);
                            if (count($keys) === 2 && isset($item[$keys[0]])) {
                                $deepFields[$keys[0]] = $keys[1];
                            }
                        } else {
                            $shallowFields[] = $field;
                        }
                    }
                    Arr::forget($item, $shallowFields);
                    foreach ($deepFields as $key => $val) {
                        $item[$key] = array_map(function ($childItem) use ($val) {
                            Arr::forget($childItem, $val);
                            return $childItem;
                        }, $item[$key]);
                    }

                }

                return $item;
            });
        });
    }

    public function boot()
    {
        $this->collectionMacro();
    }
}
