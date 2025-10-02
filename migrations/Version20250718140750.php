<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250718140750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_3bae0aa761220ea6');
        $this->addSql('ALTER TABLE event ALTER active DROP DEFAULT');
        $this->addSql('CREATE INDEX IDX_3BAE0AA761220EA6 ON event (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_3BAE0AA761220EA6');
        $this->addSql('ALTER TABLE event ALTER active SET DEFAULT false');
        $this->addSql('CREATE UNIQUE INDEX uniq_3bae0aa761220ea6 ON event (creator_id)');
    }
}
