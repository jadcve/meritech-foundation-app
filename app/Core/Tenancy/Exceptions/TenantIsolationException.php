<?php

namespace App\Core\Tenancy\Exceptions;

use RuntimeException;

class TenantIsolationException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('The operation crosses the active tenant boundary.');
    }
}
