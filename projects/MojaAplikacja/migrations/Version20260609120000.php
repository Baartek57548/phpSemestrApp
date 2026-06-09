<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260609120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates post and comment tables for blog content.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(180) NOT NULL, excerpt LONGTEXT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \"(DC2Type:datetime_immutable)\", updated_at DATETIME DEFAULT NULL COMMENT \"(DC2Type:datetime_immutable)\", UNIQUE INDEX UNIQ_POST_SLUG (slug), INDEX IDX_POST_AUTHOR (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, author_id INT NOT NULL, content LONGTEXT NOT NULL, rating SMALLINT NOT NULL, created_at DATETIME NOT NULL COMMENT \"(DC2Type:datetime_immutable)\", INDEX IDX_COMMENT_POST (post_id), INDEX IDX_COMMENT_AUTHOR (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_POST_AUTHOR FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_COMMENT_POST FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_COMMENT_AUTHOR FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_COMMENT_POST');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_COMMENT_AUTHOR');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_POST_AUTHOR');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE post');
    }
}
