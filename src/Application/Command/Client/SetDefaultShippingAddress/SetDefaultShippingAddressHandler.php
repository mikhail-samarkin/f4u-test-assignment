<?php

declare(strict_types=1);

namespace App\Application\Command\Client\SetDefaultShippingAddress;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class SetDefaultShippingAddressHandler implements CommandHandlerInterface
{
    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(SetDefaultShippingAddressCommand $command): void
    {
        $clientId = $command->getClientId();

        $client = $this->clientRepository->get($clientId);
        $client->setDefaultShippingAddress($command->getAddress());

        $this->clientRepository->save($client);
    }
}
