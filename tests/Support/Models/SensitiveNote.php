<?php

namespace Tests\Support\Models;

use App\Core\Security\Concerns\ProtectsSensitiveAttributes;
use Illuminate\Database\Eloquent\Model;

class SensitiveNote extends Model
{
    use ProtectsSensitiveAttributes;

    public $timestamps = false;

    protected $guarded = [];

    /**
     * @var array<int, string>
     */
    protected array $sensitive = ['secret'];
}
