<?php

declare(strict_types=1);

namespace App\Application\Query\Client\GetClient;

use App\Application\Query\QueryHandlerInterface;
use App\Domain\Client\Client;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;

class GetClientHandler implements QueryHandlerInterface
{
    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(GetClientQuery $query): Client
    {
        $clientId = $query->getClientId();

        return $this->clientRepository->get($clientId);
    }
}
