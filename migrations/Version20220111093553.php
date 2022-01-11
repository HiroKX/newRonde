<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220111093553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE archive (id INT AUTO_INCREMENT NOT NULL, denom VARCHAR(255) NOT NULL, annee INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, annee_id INT NOT NULL, titre VARCHAR(255) NOT NULL, utitre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date_add DATETIME NOT NULL, INDEX IDX_23A0E66C54C8C93 (type_id), INDEX IDX_23A0E66543EC5F0 (annee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_file_attachment (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_237232937294869C (article_id), INDEX IDX_237232939D1F836B (attachments_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_image_attachment (article_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_2D5B5FE7294869C (article_id), INDEX IDX_2D5B5FE9D1F836B (attachments_id), PRIMARY KEY(article_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attachment (id INT AUTO_INCREMENT NOT NULL, original_filename VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, taille INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66543EC5F0 FOREIGN KEY (annee_id) REFERENCES archive (id)');
        $this->addSql('ALTER TABLE article_file_attachment ADD CONSTRAINT FK_237232937294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_file_attachment ADD CONSTRAINT FK_237232939D1F836B FOREIGN KEY (attachments_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE article_image_attachment ADD CONSTRAINT FK_2D5B5FE7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_image_attachment ADD CONSTRAINT FK_2D5B5FE9D1F836B FOREIGN KEY (attachments_id) REFERENCES attachment (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66543EC5F0');
        $this->addSql('ALTER TABLE article_file_attachment DROP FOREIGN KEY FK_237232937294869C');
        $this->addSql('ALTER TABLE article_image_attachment DROP FOREIGN KEY FK_2D5B5FE7294869C');
        $this->addSql('ALTER TABLE article_file_attachment DROP FOREIGN KEY FK_237232939D1F836B');
        $this->addSql('ALTER TABLE article_image_attachment DROP FOREIGN KEY FK_2D5B5FE9D1F836B');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66C54C8C93');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE archive');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_file_attachment');
        $this->addSql('DROP TABLE article_image_attachment');
        $this->addSql('DROP TABLE attachment');
        $this->addSql('DROP TABLE type');
    }
}
