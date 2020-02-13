<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Person;

use Assert\Assertion;

class LastName
{
    private string $lastName;

    public function __construct(string $lastName)
    {
        Assertion::notEmpty($lastName);

        $this->lastName = $lastName;
    }

    public function getValue(): string
    {
        return $this->lastName;
    }
}
