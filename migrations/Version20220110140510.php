<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110140510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_attachment DROP FOREIGN KEY FK_4586083A9D1F836B');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A9D1F836B FOREIGN KEY (attachments_id) REFERENCES article (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_attachment DROP FOREIGN KEY FK_4586083A9D1F836B');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A9D1F836B FOREIGN KEY (attachments_id) REFERENCES attachments (id)');
    }
}
