<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220112105633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert data';
    }

    public function up(Schema $schema): void
    {
        // Type
        $this->addSql('INSERT INTO `type` (code, nom) VALUES ("ART", "Article")');
        $this->addSql('INSERT INTO `type` (code, nom) VALUES ("GAL", "Gallerie")');
        $this->addSql('INSERT INTO `type` (code, nom) VALUES ("ETA", "Zone Etalonnage")');
        $this->addSql('INSERT INTO `type` (code, nom) VALUES ("REG", "Règlement")');
        $this->addSql('INSERT INTO `type` (code, nom) VALUES ("ENG", "Engagement")');
        $this->addSql('INSERT INTO `type` (code, nom) VALUES ("RES", "Résultat")');

        // Achive
        $this->addSql('INSERT INTO `archive` (denom, annee) VALUES ("année 2020", 2020)');
    }

    public function down(Schema $schema): void
    {
        // no come back possible :p
    }
}
