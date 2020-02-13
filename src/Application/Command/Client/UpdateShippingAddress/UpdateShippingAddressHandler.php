<?php

declare(strict_types=1);

namespace App\Application\Command\Client\UpdateShippingAddress;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Client\Repository\ClientRepositoryInterface;

class UpdateShippingAddressHandler implements CommandHandlerInterface
{
    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(UpdateShippingAddressCommand $command): void
    {
        $clientId = $command->getClientId();

        $client = $this->clientRepository->get($clientId);
        $client->updateShippingAddress($command->getAddressOld(), $command->getAddressNew());

        $this->clientRepository->save($client);
    }
}
