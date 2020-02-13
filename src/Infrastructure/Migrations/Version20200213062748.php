<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200213062748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'sqlite',
            'Migration can only be executed safely on \'sqlite\'.'
        );

        $this->addSql('CREATE TABLE shipping_address (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            country VARCHAR(255) NOT NULL,
            city VARCHAR(255) NOT NULL,
            zipcode INTEGER NOT NULL,
            street VARCHAR(255) NOT NULL,
            is_default BOOLEAN NOT NULL,
            client_id VARCHAR(255) DEFAULT NULL
        )');
        $this->addSql('CREATE TABLE client (
            id VARCHAR(255) NOT NULL,
            fist_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'sqlite',
            'Migration can only be executed safely on \'sqlite\'.'
        );

        $this->addSql('DROP TABLE shipping_address');
        $this->addSql('DROP TABLE client');
    }
}
