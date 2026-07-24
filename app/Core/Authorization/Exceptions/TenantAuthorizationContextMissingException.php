<?php

namespace App\Core\Authorization\Exceptions;

use RuntimeException;

class TenantAuthorizationContextMissingException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Tenant authorization requires an active tenant context.');
    }
}
