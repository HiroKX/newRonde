<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110143731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_file_attachment (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_237232937294869C (article_id), INDEX IDX_237232939D1F836B (attachments_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_image_attachment (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_2D5B5FE7294869C (article_id), INDEX IDX_2D5B5FE9D1F836B (attachments_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_file_attachment ADD CONSTRAINT FK_237232937294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_file_attachment ADD CONSTRAINT FK_237232939D1F836B FOREIGN KEY (attachments_id) REFERENCES attachments (id)');
        $this->addSql('ALTER TABLE article_image_attachment ADD CONSTRAINT FK_2D5B5FE7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_image_attachment ADD CONSTRAINT FK_2D5B5FE9D1F836B FOREIGN KEY (attachments_id) REFERENCES attachments (id)');
        $this->addSql('DROP TABLE article_attachment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_attachment (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_4586083A7294869C (article_id), INDEX IDX_4586083A9D1F836B (attachments_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A9D1F836B FOREIGN KEY (attachments_id) REFERENCES attachments (id)');
        $this->addSql('DROP TABLE article_file_attachment');
        $this->addSql('DROP TABLE article_image_attachment');
    }
}
