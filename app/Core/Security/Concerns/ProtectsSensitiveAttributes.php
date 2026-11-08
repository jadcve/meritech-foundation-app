<?php

namespace App\Core\Security\Concerns;

trait ProtectsSensitiveAttributes
{
    public function initializeProtectsSensitiveAttributes(): void
    {
        foreach ($this->sensitive ?? [] as $key => $value) {
            if (is_int($key)) {
                $this->mergeCasts([$value => 'encrypted']);

                continue;
            }

            $this->mergeCasts([$key => $value]);
        }
    }
}
