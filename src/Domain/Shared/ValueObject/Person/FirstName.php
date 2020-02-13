<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Person;

use Assert\Assertion;

class FirstName
{
    private string $firstName;

    public function __construct(string $firstName)
    {
        Assertion::notEmpty($firstName);

        $this->firstName = $firstName;
    }

    public function getValue(): string
    {
        return $this->firstName;
    }
}
