<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191105093745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, post_id INTEGER NOT NULL, user_id INTEGER NOT NULL, content VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__post AS SELECT id, title, content, featured_image, file FROM post');
        $this->addSql('DROP TABLE post');
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content CLOB NOT NULL COLLATE BINARY, title VARCHAR(255) NOT NULL, featured_image VARCHAR(255) DEFAULT NULL, file VARCHAR(100) DEFAULT NULL)');
        $this->addSql('INSERT INTO post (id, title, content, featured_image, file) SELECT id, title, content, featured_image, file FROM __temp__post');
        $this->addSql('DROP TABLE __temp__post');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TEMPORARY TABLE __temp__post AS SELECT id, title, content, featured_image, file FROM post');
        $this->addSql('DROP TABLE post');
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content CLOB NOT NULL, title VARCHAR(190) NOT NULL COLLATE BINARY, featured_image VARCHAR(190) DEFAULT NULL COLLATE BINARY, file VARCHAR(190) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO post (id, title, content, featured_image, file) SELECT id, title, content, featured_image, file FROM __temp__post');
        $this->addSql('DROP TABLE __temp__post');
    }
}
