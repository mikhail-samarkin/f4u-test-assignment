<?php

declare(strict_types=1);

namespace App\Domain\Client\Repository;

use App\Domain\Client\Client;
use App\Domain\Client\ValueObject\ClientId;

interface ClientRepositoryInterface
{
    public function get(ClientId $clientId): Client;

    /**
     * @return object[]
     */
    public function all(): array;

    public function save(Client $client): void;

    public function delete(ClientId $clientId): void;
}
