<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181020184002 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE oiseau (id INT AUTO_INCREMENT NOT NULL, famille VARCHAR(255) DEFAULT NULL, cd_ref VARCHAR(255) DEFAULT NULL, rang VARCHAR(255) DEFAULT NULL, lb_nom VARCHAR(255) DEFAULT NULL, lb_auteur VARCHAR(255) DEFAULT NULL, nom_complet_html VARCHAR(255) DEFAULT NULL, nom_valide VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, nom_complet VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lw_article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, photo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, alive TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, article_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE observation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, oiseau_id INT NOT NULL, nom VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, coordonnees_gps VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, valide TINYINT(1) NOT NULL, INDEX IDX_C576DBE0A76ED395 (user_id), INDEX IDX_C576DBE04E7DA765 (oiseau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES jd_users (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES lw_article (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0A76ED395 FOREIGN KEY (user_id) REFERENCES jd_users (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE04E7DA765 FOREIGN KEY (oiseau_id) REFERENCES oiseau (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE04E7DA765');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('DROP TABLE oiseau');
        $this->addSql('DROP TABLE lw_article');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE observation');
    }
}
