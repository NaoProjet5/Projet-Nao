<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181119093804 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lw_article ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lw_article ADD CONSTRAINT FK_AFC312E667B3B43D FOREIGN KEY (users_id) REFERENCES jd_users (id)');
        $this->addSql('CREATE INDEX IDX_AFC312E667B3B43D ON lw_article (users_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lw_article DROP FOREIGN KEY FK_AFC312E667B3B43D');
        $this->addSql('DROP INDEX IDX_AFC312E667B3B43D ON lw_article');
        $this->addSql('ALTER TABLE lw_article DROP users_id');
    }
}
