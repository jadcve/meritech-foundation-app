<?php

namespace App\Core\Capabilities\Support;

final readonly class CapabilityDefinition
{
    public function __construct(
        public string $name,
        public bool $enabled = false,
        public array $metadata = [],
    ) {}

    public function withEnabled(bool $enabled): self
    {
        return new self($this->name, $enabled, $this->metadata);
    }

    public function withMetadata(array $metadata): self
    {
        return new self($this->name, $this->enabled, $metadata);
    }
}
