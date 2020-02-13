<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Person;

class Person
{
    private FirstName $firstName;

    private LastName $lastName;

    public function __construct(FirstName $firstName, LastName $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }
}
