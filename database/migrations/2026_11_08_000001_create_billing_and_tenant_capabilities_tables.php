<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('plan_capabilities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->string('capability');
            $table->boolean('enabled')->default(false);
            $table->timestamps();

            $table->unique(['plan_id', 'capability']);
        });

        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('active');
            $table->string('provider')->default('local');
            $table->string('external_reference')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('tenant_capabilities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('capability');
            $table->boolean('enabled')->default(false);
            $table->timestamps();

            $table->unique(['tenant_id', 'capability']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_capabilities');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plan_capabilities');
        Schema::dropIfExists('plans');
    }
};
