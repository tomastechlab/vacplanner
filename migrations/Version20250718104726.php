<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250718104726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poll_option ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB67B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B68343EB67B3B43D ON poll_option (users_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE poll_option DROP CONSTRAINT FK_B68343EB67B3B43D');
        $this->addSql('DROP INDEX IDX_B68343EB67B3B43D');
        $this->addSql('ALTER TABLE poll_option DROP users_id');
    }
}
