<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201101184800 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, repo_name VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, commit_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commit (id INT AUTO_INCREMENT NOT NULL, push_event_id INT DEFAULT NULL, created_at DATETIME NOT NULL, message LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, commit_id VARCHAR(255) NOT NULL, INDEX IDX_4ED42EAD15A705D1 (push_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pull_request_event (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, repo_name VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, number_of_commits INT NOT NULL, number_of_comments INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE push_event (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, repo_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commit ADD CONSTRAINT FK_4ED42EAD15A705D1 FOREIGN KEY (push_event_id) REFERENCES push_event (id)');
        $this->addSql('DROP TABLE commit_comment');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commit DROP FOREIGN KEY FK_4ED42EAD15A705D1');
        $this->addSql('CREATE TABLE commit_comment (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, commit_message LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE commit');
        $this->addSql('DROP TABLE pull_request_event');
        $this->addSql('DROP TABLE push_event');
    }
}
