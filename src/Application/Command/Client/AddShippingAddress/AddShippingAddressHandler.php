<?php

declare(strict_types=1);

namespace App\Application\Command\Client\AddShippingAddress;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class AddShippingAddressHandler implements CommandHandlerInterface
{
    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(AddShippingAddressCommand $command): void
    {
        $clientId = $command->getClientId();

        $client = $this->clientRepository->get($clientId);
        $client->addShippingAddress($command->getAddress());

        $this->clientRepository->save($client);
    }
}
