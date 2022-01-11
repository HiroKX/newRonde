<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110135549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_attachment DROP FOREIGN KEY FK_4586083A464E68B');
        $this->addSql('DROP INDEX IDX_4586083A464E68B ON article_attachment');
        $this->addSql('ALTER TABLE article_attachment DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE article_attachment CHANGE attachment_id attachments_id INT NOT NULL');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A9D1F836B FOREIGN KEY (attachments_id) REFERENCES attachments (id)');
        $this->addSql('CREATE INDEX IDX_4586083A9D1F836B ON article_attachment (attachments_id)');
        $this->addSql('ALTER TABLE article_attachment ADD PRIMARY KEY (article_id, attachments_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_attachment DROP FOREIGN KEY FK_4586083A9D1F836B');
        $this->addSql('DROP INDEX IDX_4586083A9D1F836B ON article_attachment');
        $this->addSql('ALTER TABLE article_attachment DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE article_attachment CHANGE attachments_id attachment_id INT NOT NULL');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A464E68B FOREIGN KEY (attachment_id) REFERENCES attachments (id)');
        $this->addSql('CREATE INDEX IDX_4586083A464E68B ON article_attachment (attachment_id)');
        $this->addSql('ALTER TABLE article_attachment ADD PRIMARY KEY (article_id, attachment_id)');
    }
}
