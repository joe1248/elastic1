<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180214144116 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, publisher_id INT DEFAULT NULL, author_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, cover_url VARCHAR(255) NOT NULL, isbn VARCHAR(30) NOT NULL, deleted TINYINT(1) NOT NULL, featured TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4A1B2A92CC1CF4E6 (isbn), INDEX IDX_4A1B2A9240C86FCE (publisher_id), INDEX IDX_4A1B2A92F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publishers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, deleted TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_842DC37B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A9240C86FCE FOREIGN KEY (publisher_id) REFERENCES publishers (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92F675F31B');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A9240C86FCE');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE publishers');
    }
}
