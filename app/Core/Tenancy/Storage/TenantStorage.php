<?php

namespace App\Core\Tenancy\Storage;

use App\Core\Tenancy\Exceptions\MissingTenantContextException;
use App\Core\Tenancy\Services\TenantContext;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class TenantStorage
{
    public function __construct(private readonly TenantContext $context) {}

    public function put(string $path, string $contents): string
    {
        $relative = $this->path($path);
        $this->disk()->put($relative, $contents);

        return $relative;
    }

    public function get(string $path): ?string
    {
        $relative = $this->path($path);

        if (! $this->disk()->exists($relative)) {
            return null;
        }

        return $this->disk()->get($relative);
    }

    public function exists(string $path): bool
    {
        return $this->disk()->exists($this->path($path));
    }

    public function path(string $path): string
    {
        $tenantId = $this->context->id();

        if ($tenantId === null) {
            throw new MissingTenantContextException;
        }

        $clean = str_replace('\\', '/', $path);
        $clean = str_replace('..', '', $clean);
        $clean = ltrim($clean, '/');

        return 'tenants/'.$tenantId.'/'.$clean;
    }

    private function disk(): Filesystem
    {
        return Storage::disk(config('foundation.tenancy.disk', 'local'));
    }
}
