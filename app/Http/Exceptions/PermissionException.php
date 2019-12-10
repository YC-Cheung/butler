<?php

namespace App\Http\Exceptions;

use Exception;

class PermissionException extends Exception
{
    protected $code = 403;
    protected $message = 'Forbidden';
}
