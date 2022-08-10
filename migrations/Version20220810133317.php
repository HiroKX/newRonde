<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810133317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE article_file_attachment');
        $this->addSql('DROP TABLE article_image_attachment');
        $this->addSql('DROP TABLE article_image_attachment_no_gallery');
        $this->addSql('ALTER TABLE attachment ADD article_files_id INT DEFAULT NULL, ADD article_images_id INT DEFAULT NULL, ADD article_images_gallery_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BBF60EAF44 FOREIGN KEY (article_files_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BBE7A55C1 FOREIGN KEY (article_images_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB7C5AF6FD FOREIGN KEY (article_images_gallery_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_795FD9BBF60EAF44 ON attachment (article_files_id)');
        $this->addSql('CREATE INDEX IDX_795FD9BBE7A55C1 ON attachment (article_images_id)');
        $this->addSql('CREATE INDEX IDX_795FD9BB7C5AF6FD ON attachment (article_images_gallery_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_file_attachment (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_237232939D1F836B (attachments_id), INDEX IDX_237232937294869C (article_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE article_image_attachment (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_2D5B5FE9D1F836B (attachments_id), INDEX IDX_2D5B5FE7294869C (article_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE article_image_attachment_no_gallery (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_35ABAB8A9D1F836B (attachments_id), INDEX IDX_35ABAB8A7294869C (article_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_file_attachment ADD CONSTRAINT FK_237232937294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_file_attachment ADD CONSTRAINT FK_237232939D1F836B FOREIGN KEY (attachments_id) REFERENCES attachment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_image_attachment ADD CONSTRAINT FK_2D5B5FE7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_image_attachment ADD CONSTRAINT FK_2D5B5FE9D1F836B FOREIGN KEY (attachments_id) REFERENCES attachment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_image_attachment_no_gallery ADD CONSTRAINT FK_35ABAB8A7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_image_attachment_no_gallery ADD CONSTRAINT FK_35ABAB8A9D1F836B FOREIGN KEY (attachments_id) REFERENCES attachment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BBF60EAF44');
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BBE7A55C1');
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BB7C5AF6FD');
        $this->addSql('DROP INDEX IDX_795FD9BBF60EAF44 ON attachment');
        $this->addSql('DROP INDEX IDX_795FD9BBE7A55C1 ON attachment');
        $this->addSql('DROP INDEX IDX_795FD9BB7C5AF6FD ON attachment');
        $this->addSql('ALTER TABLE attachment DROP article_files_id, DROP article_images_id, DROP article_images_gallery_id');
    }
}
