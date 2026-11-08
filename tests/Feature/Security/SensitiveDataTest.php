<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Support\Models\SensitiveNote;
use Tests\TestCase;

class SensitiveDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_sensitive_attribute_pattern_encrypts_at_rest(): void
    {
        Schema::create('sensitive_notes', function ($table): void {
            $table->id();
            $table->text('secret')->nullable();
        });

        $note = SensitiveNote::query()->create(['secret' => 'diagnostico-ficticio']);

        $this->assertSame('diagnostico-ficticio', $note->fresh()->secret);
        $stored = DB::table('sensitive_notes')->where('id', $note->getKey())->value('secret');
        $this->assertIsString($stored);
        $this->assertStringNotContainsString('diagnostico-ficticio', $stored);
    }
}
