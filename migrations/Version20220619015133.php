<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619015133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_5A8A6C8DF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user DROP username, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, content LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, date DATETIME NOT NULL, INDEX IDX_885DBAFAF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE post');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(180) NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}
