<?php

namespace App\Providers;

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
    }

    public function boot()
    {
        $this->collectionMacro();
    }
}
