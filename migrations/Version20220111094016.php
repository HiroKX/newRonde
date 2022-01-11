<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220111094016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert data';
    }

    public function up(Schema $schema): void
    {
        // Type
        $this->addSql('INSERT INTO `type` (nom) VALUES ('')');

        // Achive
        $this->addSql('INSERT INTO `archive` (denom, annee) VALUES ('')');
    }

    public function down(Schema $schema): void
    {
        // no come back possible :p
    }
}
