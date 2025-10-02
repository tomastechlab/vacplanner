<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250718105304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE poll_option_user (poll_option_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(poll_option_id, user_id))');
        $this->addSql('CREATE INDEX IDX_57FF0BA86C13349B ON poll_option_user (poll_option_id)');
        $this->addSql('CREATE INDEX IDX_57FF0BA8A76ED395 ON poll_option_user (user_id)');
        $this->addSql('ALTER TABLE poll_option_user ADD CONSTRAINT FK_57FF0BA86C13349B FOREIGN KEY (poll_option_id) REFERENCES poll_option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll_option_user ADD CONSTRAINT FK_57FF0BA8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE poll_option_user DROP CONSTRAINT FK_57FF0BA86C13349B');
        $this->addSql('ALTER TABLE poll_option_user DROP CONSTRAINT FK_57FF0BA8A76ED395');
        $this->addSql('DROP TABLE poll_option_user');
    }
}
