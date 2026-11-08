<?php

namespace App\Core\Tenancy\Cache;

use App\Core\Tenancy\Exceptions\MissingTenantContextException;
use App\Core\Tenancy\Services\TenantContext;
use Illuminate\Contracts\Cache\Repository;

class TenantCache
{
    public function __construct(
        private readonly TenantContext $context,
        private readonly Repository $cache,
    ) {}

    public function put(string $key, mixed $value, mixed $ttl = null): void
    {
        $this->cache->put($this->qualify($key), $value, $ttl);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache->get($this->qualify($key), $default);
    }

    public function forget(string $key): bool
    {
        return $this->cache->forget($this->qualify($key));
    }

    public function qualify(string $key): string
    {
        $tenantId = $this->context->id();

        if ($tenantId === null) {
            throw new MissingTenantContextException;
        }

        return 'tenant:'.$tenantId.':'.$key;
    }
}
