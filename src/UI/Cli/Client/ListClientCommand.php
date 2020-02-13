<?php

declare(strict_types=1);

namespace App\UI\Cli\Client;

use App\Application\Query\Client\GetClients\GetClientsQuery;
use App\Domain\Client\Client;
use App\Domain\Shared\ValueObject\Address\Address;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class ListClientCommand extends Command
{
    use HandleTrait;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MessageBusInterface $commandBus, LoggerInterface $logger)
    {
        parent::__construct();
        $this->messageBus = $commandBus;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('client:list')
            ->setDescription('Get all clients.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Client[] $clients */
        $clients = $this->handle(new GetClientsQuery());
        foreach ($clients as $client) {
            $personInformation = $client->person();
            $defaultShippingAddress = $client->defaultShippingAddress();
            $output->writeln('Id: ' . $client->id()->getUUID());
            $output->writeln(
                'LastName: ' . $personInformation->getLastName()->getValue()
            );
            $output->writeln(
                'FirstName: ' . $personInformation->getFirstName()->getValue() .
                '. LastName: ' . $personInformation->getLastName()->getValue()
            );

            $output->writeln('Default shipping address:');
            if ($defaultShippingAddress instanceof Address) {
                $this->writeAddress($output, $defaultShippingAddress);
            }
            $output->writeln('-------------');
        }

        $this->logger->info(__CLASS__ . ' successfully executed at ' . date('Y-m-d H:i:s'));

        return 0;
    }

    private function writeAddress(OutputInterface $output, Address $address): void
    {
        $output->writeln('Country: ' . $address->getCountry()->getValue() .
            '. City: ' . $address->getCity()->getValue() .
            '. Street: ' . $address->getStreet()->getValue() .
            '. ZipCode: ' . $address->getZipCode()->getValue());
    }
}
