<?php

namespace App\Tests\Domain\Client;

use App\Domain\Client\Client;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Shared\ValueObject\Address\Address;
use App\Domain\Shared\ValueObject\Address\City;
use App\Domain\Shared\ValueObject\Address\Country;
use App\Domain\Shared\ValueObject\Address\Street;
use App\Domain\Shared\ValueObject\Address\ZipCode;
use App\Domain\Shared\ValueObject\Person\FirstName;
use App\Domain\Shared\ValueObject\Person\LastName;
use App\Domain\Shared\ValueObject\Person\Person;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClientTest extends KernelTestCase
{
    public function testAddShippingAddress()
    {
        $client = $this->getClient();

        $address = new Address(
            new Country('test_country'),
            new City('test_city'),
            new Street('test_street'),
            new ZipCode(33241)
        );

        $actualShippingAddresses = $client->getShippingAddresses();
        $this->assertCount(0, $actualShippingAddresses);

        $client->addShippingAddress($address);

        $actualShippingAddresses = $client->getShippingAddresses();
        $this->assertCount(1, $actualShippingAddresses);

        $actualAddress = $actualShippingAddresses->first()->address();

        $this->assertSame('test_country', $actualAddress->getCountry()->getValue());
        $this->assertSame('test_city', $actualAddress->getCity()->getValue());
        $this->assertSame('test_street', $actualAddress->getStreet()->getValue());
        $this->assertSame(33241, $actualAddress->getZipCode()->getValue());
    }

    public function testShippingAddresses()
    {
        $client = $this->getClient();

        $addressOne = new Address(
            new Country('test_country1'),
            new City('test_city1'),
            new Street('test_street1'),
            new ZipCode(33241)
        );

        $addressTwo = new Address(
            new Country('test_country2'),
            new City('test_city2'),
            new Street('test_street2'),
            new ZipCode(33242)
        );

        $client->addShippingAddress($addressOne);
        $client->addShippingAddress($addressTwo);

        $actualShippingAddresses = $client->shippingAddresses();
        $this->assertCount(2, $actualShippingAddresses);

        $actualAddressOne = $actualShippingAddresses[0];

        $this->assertSame('test_country1', $actualAddressOne->getCountry()->getValue());
        $this->assertSame('test_city1', $actualAddressOne->getCity()->getValue());
        $this->assertSame('test_street1', $actualAddressOne->getStreet()->getValue());
        $this->assertSame(33241, $actualAddressOne->getZipCode()->getValue());

        $actualAddressTwo = $actualShippingAddresses[1];

        $this->assertSame('test_country2', $actualAddressTwo->getCountry()->getValue());
        $this->assertSame('test_city2', $actualAddressTwo->getCity()->getValue());
        $this->assertSame('test_street2', $actualAddressTwo->getStreet()->getValue());
        $this->assertSame(33242, $actualAddressTwo->getZipCode()->getValue());
    }

    public function testDefaultShippingAddress()
    {
        $client = $this->getClient();

        $defaultAddress = new Address(
            new Country('test_country1'),
            new City('test_city1'),
            new Street('test_street1'),
            new ZipCode(33241)
        );

        $addressTwo = new Address(
            new Country('test_country2'),
            new City('test_city2'),
            new Street('test_street2'),
            new ZipCode(33242)
        );

        $client->addShippingAddress($defaultAddress);
        $client->addShippingAddress($addressTwo);

        $defaultShippingAddress = $client->defaultShippingAddress();
        $this->assertInstanceOf(Address::class, $defaultShippingAddress);

        $this->assertSame('test_country1', $defaultShippingAddress->getCountry()->getValue());
        $this->assertSame('test_city1', $defaultShippingAddress->getCity()->getValue());
        $this->assertSame('test_street1', $defaultShippingAddress->getStreet()->getValue());
        $this->assertSame(33241, $defaultShippingAddress->getZipCode()->getValue());
    }

    public function testDeleteShippingAddress()
    {
        $client = $this->getClient();

        $addressOne = new Address(
            new Country('test_country1'),
            new City('test_city1'),
            new Street('test_street1'),
            new ZipCode(33241)
        );

        $addressTwo = new Address(
            new Country('test_country2'),
            new City('test_city2'),
            new Street('test_street2'),
            new ZipCode(33242)
        );

        $client->addShippingAddress($addressOne);
        $client->addShippingAddress($addressTwo);
        $client->deleteShippingAddress($addressOne);

        $actualShippingAddresses = $client->shippingAddresses();
        $this->assertCount(1, $actualShippingAddresses);

        $actualAddressOne = $actualShippingAddresses[0];

        $this->assertSame('test_country2', $actualAddressOne->getCountry()->getValue());
        $this->assertSame('test_city2', $actualAddressOne->getCity()->getValue());
        $this->assertSame('test_street2', $actualAddressOne->getStreet()->getValue());
        $this->assertSame(33242, $actualAddressOne->getZipCode()->getValue());
    }

    public function testSetDefaultShippingAddress()
    {
        $client = $this->getClient();

        $defaultAddress = new Address(
            new Country('test_country1'),
            new City('test_city1'),
            new Street('test_street1'),
            new ZipCode(33241)
        );

        $addressTwo = new Address(
            new Country('test_country2'),
            new City('test_city2'),
            new Street('test_street2'),
            new ZipCode(33242)
        );

        $client->addShippingAddress($defaultAddress);
        $client->addShippingAddress($addressTwo);
        $client->setDefaultShippingAddress($addressTwo);

        $defaultShippingAddress = $client->defaultShippingAddress();
        $this->assertInstanceOf(Address::class, $defaultShippingAddress);

        $this->assertSame('test_country2', $defaultShippingAddress->getCountry()->getValue());
        $this->assertSame('test_city2', $defaultShippingAddress->getCity()->getValue());
        $this->assertSame('test_street2', $defaultShippingAddress->getStreet()->getValue());
        $this->assertSame(33242, $defaultShippingAddress->getZipCode()->getValue());
    }

    public function testUpdateShippingAddress()
    {
        $client = $this->getClient();

        $address = new Address(
            new Country('test_country1'),
            new City('test_city1'),
            new Street('test_street1'),
            new ZipCode(33241)
        );

        $client->addShippingAddress($address);

        $addressNew = new Address(
            new Country('new_country'),
            new City('new_city'),
            new Street('new_street'),
            new ZipCode(11111)
        );

        $client->updateShippingAddress($address, $addressNew);

        $actualShippingAddresses = $client->getShippingAddresses();

        $actualNewAddress = $actualShippingAddresses->first()->address();

        $this->assertSame('new_country', $actualNewAddress->getCountry()->getValue());
        $this->assertSame('new_city', $actualNewAddress->getCity()->getValue());
        $this->assertSame('new_street', $actualNewAddress->getStreet()->getValue());
        $this->assertSame(11111, $actualNewAddress->getZipCode()->getValue());
    }

    private function getClient(): Client
    {
        $clientId = new ClientId(Uuid::uuid4()->toString());
        $person = new Person(new FirstName('Test firstName'), new LastName('Test lastName'));

        return new Client($clientId, $person);
    }
}
