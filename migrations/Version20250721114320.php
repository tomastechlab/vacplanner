<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721114320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_list_comment (item_list_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(item_list_id, comment_id))');
        $this->addSql('CREATE INDEX IDX_876172A036F330DF ON item_list_comment (item_list_id)');
        $this->addSql('CREATE INDEX IDX_876172A0F8697D13 ON item_list_comment (comment_id)');
        $this->addSql('CREATE TABLE item_list_item_comment (item_list_item_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(item_list_item_id, comment_id))');
        $this->addSql('CREATE INDEX IDX_68FCFED517A7292 ON item_list_item_comment (item_list_item_id)');
        $this->addSql('CREATE INDEX IDX_68FCFED5F8697D13 ON item_list_item_comment (comment_id)');
        $this->addSql('ALTER TABLE item_list_comment ADD CONSTRAINT FK_876172A036F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_list_comment ADD CONSTRAINT FK_876172A0F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_list_item_comment ADD CONSTRAINT FK_68FCFED517A7292 FOREIGN KEY (item_list_item_id) REFERENCES item_list_item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_list_item_comment ADD CONSTRAINT FK_68FCFED5F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item_list_comment DROP CONSTRAINT FK_876172A036F330DF');
        $this->addSql('ALTER TABLE item_list_comment DROP CONSTRAINT FK_876172A0F8697D13');
        $this->addSql('ALTER TABLE item_list_item_comment DROP CONSTRAINT FK_68FCFED517A7292');
        $this->addSql('ALTER TABLE item_list_item_comment DROP CONSTRAINT FK_68FCFED5F8697D13');
        $this->addSql('DROP TABLE item_list_comment');
        $this->addSql('DROP TABLE item_list_item_comment');
    }
}
