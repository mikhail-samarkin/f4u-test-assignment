<?php

declare(strict_types=1);

namespace App\Application\Query\Client\GetClient;

use App\Domain\Client\ValueObject\ClientId;

class GetClientQuery
{
    private ClientId $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = new ClientId($clientId);
    }

    public function getClientId(): ClientId
    {
        return $this->clientId;
    }
}
