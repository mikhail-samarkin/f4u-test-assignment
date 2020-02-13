<?php

declare(strict_types=1);

namespace App\Domain\Client\ValueObject;

class IsDefault
{
    private bool $isDefault;

    public function __construct(bool $isDefault)
    {
        $this->isDefault = $isDefault;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }
}
