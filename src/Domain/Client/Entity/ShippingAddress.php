<?php

declare(strict_types=1);

namespace App\Domain\Client\Entity;

use App\Domain\Client\Client;
use App\Domain\Client\ValueObject\IsDefault;
use App\Domain\Shared\ValueObject\Address\Address;
use App\Domain\Shared\ValueObject\Address\City;
use App\Domain\Shared\ValueObject\Address\Country;
use App\Domain\Shared\ValueObject\Address\Street;
use App\Domain\Shared\ValueObject\Address\ZipCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * Part of aggregate Client
 * @ORM\Entity
 * @ORM\Table(name="shipping_address")
 */
class ShippingAddress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @ORM\Column(type="integer", length=6)
     */
    private int $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $street;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isDefault;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Client\Client", inversedBy="shippingAddresses")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private Client $client;

    public function __construct(Client $client, Address $address, IsDefault $isDefault)
    {
        $this->client = $client;
        $this->country = $address->getCountry()->getValue();
        $this->city = $address->getCity()->getValue();
        $this->street = $address->getStreet()->getValue();
        $this->zipcode = $address->getZipCode()->getValue();
        $this->isDefault = $isDefault->isDefault();
    }

    public function address(): Address
    {
        $country = new Country($this->country);
        $city = new City($this->city);
        $street = new Street($this->street);
        $zipCode = new ZipCode($this->zipcode);

        return new Address($country, $city, $street, $zipCode);
    }

    public function isDefault(): IsDefault
    {
        return new IsDefault($this->isDefault);
    }

    public function setIsDefault(IsDefault $isDefault): void
    {
        $this->isDefault = $isDefault->isDefault();
    }

    public function setAddress(Address $address): void
    {
        $this->country = $address->getCountry()->getValue();
        $this->city = $address->getCity()->getValue();
        $this->street = $address->getStreet()->getValue();
        $this->zipcode = $address->getZipCode()->getValue();
    }
}
