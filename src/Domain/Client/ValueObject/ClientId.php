<?php

declare(strict_types=1);

namespace App\Domain\Client\ValueObject;

class ClientId
{
    private string $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUUID(): string
    {
        return $this->uuid;
    }
}
