<?php

declare(strict_types=1);

namespace App\Application\Command\Client\UpdateShippingAddress;

use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Shared\ValueObject\Address\Address;
use App\Domain\Shared\ValueObject\Address\City;
use App\Domain\Shared\ValueObject\Address\Country;
use App\Domain\Shared\ValueObject\Address\Street;
use App\Domain\Shared\ValueObject\Address\ZipCode;

class UpdateShippingAddressCommand
{
    private Address $addressOld;

    private Address $addressNew;

    private ClientId $clientId;

    public function __construct(
        string $uuid,
        string $country,
        string $city,
        string $street,
        int $zipCode,
        string $countryNew,
        string $cityNew,
        string $streetNew,
        int $zipCodeNew
    ) {
        $this->addressOld = $this->buildAddress($country, $city, $street, $zipCode);
        $this->addressNew = $this->buildAddress($countryNew, $cityNew, $streetNew, $zipCodeNew);

        $this->clientId = new ClientId($uuid);
    }

    public function getAddressOld(): Address
    {
        return $this->addressOld;
    }

    public function getAddressNew(): Address
    {
        return $this->addressNew;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }

    private function buildAddress(string $country, string $city, string $street, int $zipCode): Address
    {
        $country = new Country($country);
        $city = new City($city);
        $street = new Street($street);
        $zipCode = new ZipCode($zipCode);

        return new Address($country, $city, $street, $zipCode);
    }
}
