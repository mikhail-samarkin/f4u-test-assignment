<?php

declare(strict_types=1);

namespace App\Infrastructure\Fixtures;

use App\Domain\Client\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;
use App\Domain\Shared\ValueObject\Address\Address;
use App\Domain\Shared\ValueObject\Address\City;
use App\Domain\Shared\ValueObject\Address\Country;
use App\Domain\Shared\ValueObject\Address\Street;
use App\Domain\Shared\ValueObject\Address\ZipCode;
use App\Domain\Shared\ValueObject\Person\FirstName;
use App\Domain\Shared\ValueObject\Person\LastName;
use App\Domain\Shared\ValueObject\Person\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;

class ClientFixtures extends Fixture
{
    private ClientRepositoryInterface $clientRepository;

    private Generator $faker;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->faker = Factory::create();
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 10; $i++) {
            $client = $this->generateRandomClient();
            $client = $this->addRandomSippingAddresses($client);

            $this->clientRepository->save($client);
        }
    }

    private function generateRandomClient(): Client
    {
        $clientId = $this->generateClientId();
        $person = $this->generatePerson();

        return new Client($clientId, $person);
    }

    private function generateClientId(): ClientId
    {
        $uuid = Uuid::uuid4()->toString();

        return new ClientId($uuid);
    }

    private function generatePerson(): Person
    {
        $firstName = new FirstName($this->faker->firstName);
        $lastName = new LastName($this->faker->lastName);

        return new Person($firstName, $lastName);
    }

    private function addRandomSippingAddresses(Client $client): Client
    {
        $randCountAddresses = \random_int(0, Client::MAX_COUNT_SHIPPING_ADDRESSES);

        for ($i = 1; $i <= $randCountAddresses; $i++) {
            $address = $this->generateAddress();
            $client->addShippingAddress($address);
        }

        return $client;
    }

    private function generateAddress(): Address
    {
        $country = new Country($this->faker->country);
        $city = new City($this->faker->city);
        $street = new Street($this->faker->streetName);
        $zipCode = new ZipCode((int)$this->faker->postcode);

        return new Address($country, $city, $street, $zipCode);
    }
}
