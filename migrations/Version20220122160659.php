<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220122160659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_image_attachment_no_gallery (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_35ABAB8A7294869C (article_id), INDEX IDX_35ABAB8A9D1F836B (attachments_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_image_attachment_no_gallery ADD CONSTRAINT FK_35ABAB8A7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_image_attachment_no_gallery ADD CONSTRAINT FK_35ABAB8A9D1F836B FOREIGN KEY (attachments_id) REFERENCES attachment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article_image_attachment_no_gallery');
    }
}
