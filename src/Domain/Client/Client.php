<?php

declare(strict_types=1);

namespace App\Domain\Client;

use App\Domain\Client\Entity\ShippingAddress;
use App\Domain\Client\Exception\DeleteShippingAddressNotAvailableException;
use App\Domain\Client\Exception\MaximumQuantityShippingAddressExceededException;
use App\Domain\Client\Exception\ShippingAddressNotFoundException;
use App\Domain\Client\Exception\ThisAddressAlreadyIsDefaultException;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Client\ValueObject\IsDefault;
use App\Domain\Shared\ValueObject\Address\Address;
use App\Domain\Shared\ValueObject\Person\FirstName;
use App\Domain\Shared\ValueObject\Person\LastName;
use App\Domain\Shared\ValueObject\Person\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client
{
    public const MAX_COUNT_SHIPPING_ADDRESSES = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     *
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $fistName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Client\Entity\ShippingAddress",
     *     fetch="EAGER",
     *     mappedBy="client",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     */
    private Collection $shippingAddresses;

    public function __construct(ClientId $clientId, Person $person)
    {
        $this->id = $clientId->getUUID();
        $this->fistName = $person->getFirstName()->getValue();
        $this->lastName = $person->getLastName()->getValue();

        $this->shippingAddresses = new ArrayCollection();
    }

    public function addShippingAddress(Address $address): void
    {
        $countShippingAddress = \count($this->shippingAddresses);

        if ($countShippingAddress === self::MAX_COUNT_SHIPPING_ADDRESSES) {
            throw new MaximumQuantityShippingAddressExceededException();
        }

        $isDefault = $countShippingAddress === 0 ? new IsDefault(true) : new IsDefault(false);

        $shippingAddress = new ShippingAddress($this, $address, $isDefault);
        $this->shippingAddresses->add($shippingAddress);
    }

    public function id(): ClientId
    {
        return new ClientId($this->id);
    }

    public function person(): Person
    {
        return new Person(new FirstName($this->fistName), new LastName($this->lastName));
    }

    public function getShippingAddresses(): Collection
    {
        return $this->shippingAddresses;
    }

    /**
     * @return Address[]
     */
    public function shippingAddresses(): array
    {
        $addresses = [];

        /** @var ShippingAddress $shippingAddress */
        foreach ($this->shippingAddresses->getValues() as $shippingAddress) {
            $addresses [] = $shippingAddress->address();
        }

        return $addresses;
    }

    public function defaultShippingAddress(): ?Address
    {
        if ($shippingAddress = $this->findDefaultShippingAddress()) {
            return $shippingAddress->address();
        }

        return null;
    }

    public function deleteShippingAddress(Address $address): void
    {
        if (\count($this->shippingAddresses) === 1) {
            throw new DeleteShippingAddressNotAvailableException();
        }

        if (!$candidateToDelete = $this->findAddress($address)) {
            throw new ShippingAddressNotFoundException();
        }

        $isDefault = $candidateToDelete->isDefault()->isDefault();

        $this->shippingAddresses->removeElement($candidateToDelete);

        if ($isDefault) {
            /** @var ShippingAddress $candidateToDefaultShippingAddress */
            $candidateToDefaultShippingAddress = $this->shippingAddresses->first();
            $candidateToDefaultShippingAddress->setIsDefault(new IsDefault(true));
        }
    }

    public function setDefaultShippingAddress(Address $address): void
    {
        if (!$candidateToDefaultShippingAddress = $this->findAddress($address)) {
            throw new ShippingAddressNotFoundException();
        }

        if ($candidateToDefaultShippingAddress->isDefault()->isDefault() === true) {
            throw new ThisAddressAlreadyIsDefaultException();
        }

        if ($defaultShippingAddress = $this->findDefaultShippingAddress()) {
            $defaultShippingAddress->setIsDefault(new IsDefault(false));
        }

        $candidateToDefaultShippingAddress->setIsDefault(new IsDefault(true));
    }

    public function updateShippingAddress(Address $addressOld, Address $addressNew): void
    {
        if (!$candidateToUpdate = $this->findAddress($addressOld)) {
            throw new ShippingAddressNotFoundException();
        }

        $candidateToUpdate->setAddress($addressNew);
    }

    private function findAddress(Address $address): ?ShippingAddress
    {
        /** @var ShippingAddress $shippingAddress */
        foreach ($this->shippingAddresses->getValues() as $shippingAddress) {
            if ($shippingAddress->address()->compareTo($address)) {
                return $shippingAddress;
            }
        }

        return null;
    }

    private function findDefaultShippingAddress(): ?ShippingAddress
    {
        /** @var ShippingAddress $shippingAddress */
        foreach ($this->shippingAddresses->getValues() as $shippingAddress) {
            if ($shippingAddress->isDefault()->isDefault() === true) {
                return $shippingAddress;
            }
        }

        return null;
    }
}
