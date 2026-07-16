<?php

namespace App\Core\Tenancy\Exceptions;

use RuntimeException;

class TenantNotResolvedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('A tenant is required for this route, but no active tenant membership could be resolved.');
    }
}
