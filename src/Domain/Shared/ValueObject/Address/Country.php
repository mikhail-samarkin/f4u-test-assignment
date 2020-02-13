<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Address;

use Assert\Assertion;

class Country
{
    private string $country;

    public function __construct(string $country)
    {
        Assertion::notEmpty($country);

        $this->country = $country;
    }

    public function getValue(): string
    {
        return $this->country;
    }
}
