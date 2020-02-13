<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Address;

use Assert\Assertion;

class City
{
    private string $city;

    public function __construct(string $city)
    {
        Assertion::notEmpty($city);

        $this->city = $city;
    }

    public function getValue(): string
    {
        return $this->city;
    }
}
