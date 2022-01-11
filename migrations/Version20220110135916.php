<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110135916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachments DROP FOREIGN KEY FK_47C4FAD67294869C');
        $this->addSql('DROP INDEX IDX_47C4FAD67294869C ON attachments');
        $this->addSql('ALTER TABLE attachments DROP article_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachments ADD article_id INT NOT NULL');
        $this->addSql('ALTER TABLE attachments ADD CONSTRAINT FK_47C4FAD67294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_47C4FAD67294869C ON attachments (article_id)');
    }
}
