<?php

namespace App\Core\Tenancy\Exceptions;

use RuntimeException;

class MissingTenantContextException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('A tenant context is required for this operation.');
    }
}
