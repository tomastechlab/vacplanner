<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250804072757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE item_list_item_id_seq CASCADE');
        $this->addSql('CREATE TABLE item (id SERIAL NOT NULL, item_list_id INT DEFAULT NULL, item_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F1B251E36F330DF ON item (item_list_id)');
        $this->addSql('CREATE TABLE item_comment (item_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(item_id, comment_id))');
        $this->addSql('CREATE INDEX IDX_9F297438126F525E ON item_comment (item_id)');
        $this->addSql('CREATE INDEX IDX_9F297438F8697D13 ON item_comment (comment_id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E36F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_comment ADD CONSTRAINT FK_9F297438126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_comment ADD CONSTRAINT FK_9F297438F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_list_item DROP CONSTRAINT fk_560fec0736f330df');
        $this->addSql('ALTER TABLE item_list_item_comment DROP CONSTRAINT fk_68fcfed517a7292');
        $this->addSql('ALTER TABLE item_list_item_comment DROP CONSTRAINT fk_68fcfed5f8697d13');
        $this->addSql('DROP TABLE item_list_item');
        $this->addSql('DROP TABLE item_list_item_comment');
        $this->addSql('ALTER TABLE poll ALTER poll_type DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE item_list_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE item_list_item (id SERIAL NOT NULL, item_list_id INT DEFAULT NULL, item_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_560fec0736f330df ON item_list_item (item_list_id)');
        $this->addSql('CREATE TABLE item_list_item_comment (item_list_item_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(item_list_item_id, comment_id))');
        $this->addSql('CREATE INDEX idx_68fcfed517a7292 ON item_list_item_comment (item_list_item_id)');
        $this->addSql('CREATE INDEX idx_68fcfed5f8697d13 ON item_list_item_comment (comment_id)');
        $this->addSql('ALTER TABLE item_list_item ADD CONSTRAINT fk_560fec0736f330df FOREIGN KEY (item_list_id) REFERENCES item_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_list_item_comment ADD CONSTRAINT fk_68fcfed517a7292 FOREIGN KEY (item_list_item_id) REFERENCES item_list_item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_list_item_comment ADD CONSTRAINT fk_68fcfed5f8697d13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E36F330DF');
        $this->addSql('ALTER TABLE item_comment DROP CONSTRAINT FK_9F297438126F525E');
        $this->addSql('ALTER TABLE item_comment DROP CONSTRAINT FK_9F297438F8697D13');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_comment');
        $this->addSql('ALTER TABLE poll ALTER poll_type SET DEFAULT \'general\'');
    }
}
