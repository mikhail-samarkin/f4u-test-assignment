<?php

declare(strict_types=1);

namespace App\Application\Command\Client\AddShippingAddress;

use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Shared\ValueObject\Address\Address;
use App\Domain\Shared\ValueObject\Address\City;
use App\Domain\Shared\ValueObject\Address\Country;
use App\Domain\Shared\ValueObject\Address\Street;
use App\Domain\Shared\ValueObject\Address\ZipCode;

class AddShippingAddressCommand
{
    private Address $address;

    private ClientId $clientId;

    public function __construct(
        string $uuid,
        string $country,
        string $city,
        string $street,
        int $zipCode
    ) {
        $country = new Country($country);
        $city = new City($city);
        $street = new Street($street);
        $zipCode = new ZipCode($zipCode);

        $this->address = new Address($country, $city, $street, $zipCode);

        $this->clientId = new ClientId($uuid);
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }
}
