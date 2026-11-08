<?php

namespace Tests\Feature\Tenancy;

use App\Core\Audit\AuditLogger;
use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\FoundationPermissions;
use App\Core\Tenancy\Cache\TenantCache;
use App\Core\Tenancy\Exceptions\MissingTenantContextException;
use App\Core\Tenancy\Exceptions\TenantIsolationException;
use App\Core\Tenancy\Models\TenantSettings;
use App\Core\Tenancy\Services\TenantContext;
use App\Core\Tenancy\Services\TenantIsolation;
use App\Core\Tenancy\Storage\TenantStorage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\Support\CreatesTenantMemberships;
use Tests\Support\Jobs\TouchTenantSettings;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use CreatesTenantMemberships, RefreshDatabase;

    public function test_queries_fail_closed_and_do_not_leak_between_tenants(): void
    {
        $tenantA = $this->createTenant(['slug' => 'tenant-a']);
        $tenantB = $this->createTenant(['slug' => 'tenant-b']);
        $context = app(TenantContext::class);

        $context->set($tenantA);
        TenantSettings::query()->create(['tenant_id' => $tenantA->getKey(), 'primary_color' => '#111111']);
        $context->set($tenantB);
        TenantSettings::query()->create(['tenant_id' => $tenantB->getKey(), 'primary_color' => '#222222']);

        $context->clear();
        $this->assertSame([], TenantSettings::query()->pluck('primary_color')->all());

        $context->set($tenantA);
        $this->assertSame(['#111111'], TenantSettings::query()->pluck('primary_color')->all());

        $context->set($tenantB);
        $this->assertSame(['#222222'], TenantSettings::query()->pluck('primary_color')->all());
    }

    public function test_writes_cannot_cross_the_active_tenant(): void
    {
        $tenantA = $this->createTenant(['slug' => 'write-a']);
        $tenantB = $this->createTenant(['slug' => 'write-b']);
        $context = app(TenantContext::class);
        $context->set($tenantB);
        $settings = TenantSettings::query()->create(['primary_color' => '#222222']);

        $context->set($tenantA);

        $this->expectException(TenantIsolationException::class);
        $settings->primary_color = '#333333';
        $settings->save();
    }

    public function test_route_binding_hides_another_tenants_settings(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $tenantA = $this->attachTenant($userA);
        $tenantB = $this->attachTenant($userB);
        $this->seedFoundationAuthorization();
        $this->assignTenantRole($userA, $tenantA, FoundationPermissions::ADMIN);
        $this->assignTenantRole($userB, $tenantB, FoundationPermissions::ADMIN);
        $context = app(TenantContext::class);

        $context->set($tenantA);
        $settingsA = TenantSettings::query()->create(['primary_color' => '#aaaaaa']);
        $context->set($tenantB);
        $settingsB = TenantSettings::query()->create(['primary_color' => '#bbbbbb']);
        $context->clear();

        $this->actingAs($userA)
            ->getJson('/tenant/settings/record/'.$settingsA->getKey())
            ->assertOk()
            ->assertJson(['primary_color' => '#aaaaaa', 'tenant_id' => $tenantA->getKey()]);

        $this->actingAs($userA)
            ->getJson('/tenant/settings/record/'.$settingsB->getKey())
            ->assertNotFound();
    }

    public function test_cache_keys_are_isolated_by_tenant(): void
    {
        $tenantA = $this->createTenant(['slug' => 'cache-a']);
        $tenantB = $this->createTenant(['slug' => 'cache-b']);
        $context = app(TenantContext::class);
        $cache = app(TenantCache::class);

        $context->set($tenantA);
        $cache->put('note', 'secret-a');
        $qualified = $cache->qualify('note');

        $context->set($tenantB);
        $this->assertNull($cache->get('note'));
        $cache->put('note', 'secret-b');

        $context->set($tenantA);
        $this->assertSame('secret-a', $cache->get('note'));
        $this->assertNull(Cache::get('note'));
        $this->assertSame('secret-a', Cache::get($qualified));

        $context->clear();
        $this->expectException(MissingTenantContextException::class);
        $cache->get('note');
    }

    public function test_files_are_stored_under_the_tenant_directory(): void
    {
        Storage::fake('local');
        config(['foundation.tenancy.disk' => 'local']);
        $tenantA = $this->createTenant(['slug' => 'file-a']);
        $tenantB = $this->createTenant(['slug' => 'file-b']);
        $context = app(TenantContext::class);
        $storage = app(TenantStorage::class);

        $context->set($tenantA);
        $stored = $storage->put('../secret.txt', 'only-a');

        $this->assertSame('tenants/'.$tenantA->getKey().'/secret.txt', $stored);
        $this->assertSame('only-a', $storage->get('secret.txt'));

        $context->set($tenantB);
        $this->assertNull($storage->get('secret.txt'));
        $this->assertFalse($storage->exists('../secret.txt'));

        $context->clear();
        $this->expectException(MissingTenantContextException::class);
        $storage->put('note.txt', 'nope');
    }

    public function test_queued_job_sees_only_the_tenant_captured_at_dispatch(): void
    {
        $tenantA = $this->createTenant(['slug' => 'job-a']);
        $tenantB = $this->createTenant(['slug' => 'job-b']);
        $context = app(TenantContext::class);
        $context->set($tenantA);
        TenantSettings::query()->create(['primary_color' => '#111111']);
        $context->set($tenantB);
        TenantSettings::query()->create(['primary_color' => '#222222']);

        TouchTenantSettings::$colors = [];
        $context->set($tenantA);
        TouchTenantSettings::dispatch();

        $this->assertSame(['#111111'], TouchTenantSettings::$colors);
        $this->assertSame($tenantA->getKey(), $context->id());
    }

    public function test_tenant_job_without_context_fails_closed(): void
    {
        app(TenantContext::class)->clear();

        $this->expectException(MissingTenantContextException::class);
        TouchTenantSettings::dispatch();
    }

    public function test_artisan_command_binds_only_the_requested_tenant(): void
    {
        $tenantA = $this->createTenant(['slug' => 'cmd-a']);
        $tenantB = $this->createTenant(['slug' => 'cmd-b']);
        $context = app(TenantContext::class);
        $context->set($tenantA);
        TenantSettings::query()->create(['primary_color' => '#111111']);
        $context->set($tenantB);
        TenantSettings::query()->create(['primary_color' => '#222222']);
        $context->clear();

        $this->artisan('foundation:tenant-check', ['tenant' => 'cmd-a'])
            ->expectsOutput('#111111')
            ->assertSuccessful();
    }

    public function test_audit_log_is_tenant_scoped_and_encrypts_properties(): void
    {
        $tenantA = $this->createTenant(['slug' => 'audit-a']);
        $tenantB = $this->createTenant(['slug' => 'audit-b']);
        $context = app(TenantContext::class);
        $logger = app(AuditLogger::class);
        $user = User::factory()->create();
        $this->actingAs($user);

        $context->set($tenantA);
        $settings = TenantSettings::query()->create(['primary_color' => '#111111']);
        $logger->viewed($settings, ['marker' => 'visible-secret'], 'Ficha abierta');
        $logger->changed($settings, ['primary_color' => ['#000000', '#111111']], 'Color actualizado');

        $this->assertSame(2, AuditLog::query()->count());
        $raw = DB::table('audit_logs')->value('properties');
        $this->assertIsString($raw);
        $this->assertStringNotContainsString('visible-secret', $raw);

        $context->set($tenantB);
        $this->assertSame(0, AuditLog::query()->count());
    }

    public function test_platform_opt_out_can_read_across_tenants(): void
    {
        $tenantA = $this->createTenant(['slug' => 'opt-a']);
        $tenantB = $this->createTenant(['slug' => 'opt-b']);
        $context = app(TenantContext::class);
        $context->set($tenantA);
        TenantSettings::query()->create(['primary_color' => '#111111']);
        $context->set($tenantB);
        TenantSettings::query()->create(['primary_color' => '#222222']);
        $context->clear();

        $colors = app(TenantIsolation::class)->bypass(
            fn () => TenantSettings::query()->orderBy('primary_color')->pluck('primary_color')->all(),
        );

        $this->assertSame(['#111111', '#222222'], $colors);
    }
}
