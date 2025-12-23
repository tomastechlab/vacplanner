<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721114009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_list (id SERIAL NOT NULL, list_name VARCHAR(255) NOT NULL, related_to VARCHAR(255) NOT NULL, related_to_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE item_list_item (id SERIAL NOT NULL, item_list_id INT DEFAULT NULL, item_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_560FEC0736F330DF ON item_list_item (item_list_id)');
        $this->addSql('ALTER TABLE item_list_item ADD CONSTRAINT FK_560FEC0736F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment RENAME COLUMN relted_to_id TO related_to_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item_list_item DROP CONSTRAINT FK_560FEC0736F330DF');
        $this->addSql('DROP TABLE item_list');
        $this->addSql('DROP TABLE item_list_item');
        $this->addSql('ALTER TABLE comment RENAME COLUMN related_to_id TO relted_to_id');
    }
}
