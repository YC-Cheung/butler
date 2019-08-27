<?php

namespace App\Traits;

use Illuminate\Support\Facades\Request;

trait ModelHelpers
{
    protected $maxPerPage = 200;

    public function getPerPage()
    {
        $perPage = Request::get('per_page');
        $perPage = ctype_digit($perPage) ? (int)$perPage : $this->perPage;

        return min($perPage, $this->maxPerPage);
    }
}
