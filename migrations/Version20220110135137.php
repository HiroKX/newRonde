<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110135137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE archive (id INT AUTO_INCREMENT NOT NULL, denom VARCHAR(255) NOT NULL, annee INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, annee_id INT NOT NULL, titre VARCHAR(255) NOT NULL, utitre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date_add DATETIME NOT NULL, INDEX IDX_23A0E66C54C8C93 (type_id), INDEX IDX_23A0E66543EC5F0 (annee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66543EC5F0 FOREIGN KEY (annee_id) REFERENCES archive (id)');
        $this->addSql('ALTER TABLE attachments ADD CONSTRAINT FK_47C4FAD67294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A7294869C FOREIGN KEY (article_id) REFERENCES attachments (id)');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A464E68B FOREIGN KEY (attachment_id) REFERENCES attachments (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66543EC5F0');
        $this->addSql('ALTER TABLE attachments DROP FOREIGN KEY FK_47C4FAD67294869C');
        $this->addSql('DROP TABLE archive');
        $this->addSql('DROP TABLE article');
        $this->addSql('ALTER TABLE article_attachment DROP FOREIGN KEY FK_4586083A7294869C');
        $this->addSql('ALTER TABLE article_attachment DROP FOREIGN KEY FK_4586083A464E68B');
    }
}
