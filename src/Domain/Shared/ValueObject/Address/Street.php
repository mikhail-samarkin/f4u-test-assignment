<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Address;

use Assert\Assertion;

class Street
{
    private string $street;

    public function __construct(string $street)
    {
        Assertion::notEmpty($street);

        $this->street = $street;
    }

    public function getValue(): string
    {
        return $this->street;
    }
}
