<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 12)->default('en');
            $table->string('timezone')->default('UTC');
            $table->string('currency', 3)->default('USD');
            $table->string('theme')->default('system');
            $table->string('primary_color', 7)->default('#2563eb');
            $table->string('secondary_color', 7)->default('#64748b');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i');
            $table->timestamps();

            $table->unique('tenant_id');
            $table->index(['locale', 'timezone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
