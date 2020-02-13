<?php

declare(strict_types=1);

namespace App\Infrastructure\Client\Repository;

use App\Domain\Client\Client;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Client\Exception\ServiceSavingException;
use App\Domain\Client\Repository\ClientRepositoryInterface;
use App\Domain\Client\ValueObject\ClientId;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Throwable;

class ClientRepository implements ClientRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    private ObjectRepository $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Client::class);
    }

    /**
     * {@inheritdoc}
     */
    public function get(ClientId $clientId): Client
    {
        $object = $this->objectRepository->find($clientId->getUUID());

        if (!$object instanceof Client) {
            throw new ClientNotFoundException(
                sprintf('Client model with id "%s not found.', $clientId->getUUID())
            );
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->objectRepository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function save(Client $client): void
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($client);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (Throwable $throwable) {
            $this->entityManager->getConnection()->rollBack();
            throw new ServiceSavingException(
                'Repository exception: ' . $throwable->getMessage(),
                0,
                $throwable
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     */
    public function delete(ClientId $clientId): void
    {
        $this->entityManager->remove($this->get($clientId));
        $this->entityManager->flush();
    }
}
