<?php

declare(strict_types=1);

namespace App\Application\Command\Client\DeleteShippingAddress;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class DeleteShippingAddressHandler implements CommandHandlerInterface
{
    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(DeleteShippingAddressCommand $command): void
    {
        $clientId = $command->getClientId();

        $client = $this->clientRepository->get($clientId);
        $client->deleteShippingAddress($command->getAddress());

        $this->clientRepository->save($client);
    }
}
