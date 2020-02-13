<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Address;

use Assert\Assertion;

class ZipCode
{
    private int $zipCode;

    public function __construct(int $zipCode)
    {
        Assertion::integer($zipCode);

        $this->zipCode = $zipCode;
    }

    public function getValue(): int
    {
        return $this->zipCode;
    }
}
