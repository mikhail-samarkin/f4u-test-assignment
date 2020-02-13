<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject\Address;

class Address
{
    private Country $country;

    private City $city;

    private Street $street;

    private ZipCode $zipCode;

    public function __construct(Country $country, City $city, Street $street, ZipCode $zipCode)
    {
        $this->country = $country;
        $this->city = $city;
        $this->street = $street;
        $this->zipCode = $zipCode;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function getStreet(): Street
    {
        return $this->street;
    }

    public function getZipCode(): ZipCode
    {
        return $this->zipCode;
    }

    public function compareTo(Address $address): bool
    {
        return $this->country->getValue() === $address->country->getValue()
            && $this->city->getValue() === $address->city->getValue()
            && $this->street->getValue() === $address->street->getValue()
            && $this->zipCode->getValue() === $address->zipCode->getValue();
    }
}
