<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('tenant_memberships', 'tenant_memberships_legacy');

        Schema::create('tenant_memberships', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->unique(['tenant_id', 'user_id']);
            $table->index(['user_id', 'is_active', 'is_default'], 'tenant_memberships_user_resolution_index');
            $table->index(['tenant_id', 'is_active'], 'tenant_memberships_tenant_active_index');
        });

        DB::table('tenant_memberships_legacy')
            ->orderBy('tenant_id')
            ->select(['tenant_id', 'user_id', 'is_active', 'created_at', 'updated_at'])
            ->chunk(100, function ($memberships): void {
                foreach ($memberships as $membership) {
                    DB::table('tenant_memberships')->insert([
                        'tenant_id' => $membership->tenant_id,
                        'user_id' => $membership->user_id,
                        'is_active' => $membership->is_active,
                        'is_default' => false,
                        'created_at' => $membership->created_at,
                        'updated_at' => $membership->updated_at,
                    ]);
                }
            });

        Schema::drop('tenant_memberships_legacy');
    }

    public function down(): void
    {
        Schema::rename('tenant_memberships', 'tenant_memberships_v1');

        Schema::create('tenant_memberships', function (Blueprint $table): void {
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('member');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->primary(['tenant_id', 'user_id']);
            $table->index(['user_id', 'is_active'], 'tenant_memberships_user_active_index');
        });

        DB::table('tenant_memberships_v1')
            ->orderBy('id')
            ->select(['tenant_id', 'user_id', 'is_active', 'created_at', 'updated_at'])
            ->chunk(100, function ($memberships): void {
                foreach ($memberships as $membership) {
                    DB::table('tenant_memberships')->insert([
                        'tenant_id' => $membership->tenant_id,
                        'user_id' => $membership->user_id,
                        'role' => 'member',
                        'is_active' => $membership->is_active,
                        'created_at' => $membership->created_at,
                        'updated_at' => $membership->updated_at,
                    ]);
                }
            });

        Schema::drop('tenant_memberships_v1');
    }
};
