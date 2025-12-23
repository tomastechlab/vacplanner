<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250718090428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE poll (id SERIAL NOT NULL, question VARCHAR(255) NOT NULL, is_multiple_choice BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE poll_option (id SERIAL NOT NULL, poll_id INT NOT NULL, name VARCHAR(255) NOT NULL, votes INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B68343EB3C947C0F ON poll_option (poll_id)');
        $this->addSql('ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE poll_option DROP CONSTRAINT FK_B68343EB3C947C0F');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE poll_option');
    }
}
