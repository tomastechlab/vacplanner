<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223140435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id SERIAL NOT NULL, comment TEXT NOT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, related_to VARCHAR(255) NOT NULL, related_to_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE comment_user (comment_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(comment_id, user_id))');
        $this->addSql('CREATE INDEX IDX_ABA574A5F8697D13 ON comment_user (comment_id)');
        $this->addSql('CREATE INDEX IDX_ABA574A5A76ED395 ON comment_user (user_id)');
        $this->addSql('CREATE TABLE event (id SERIAL NOT NULL, creator_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, lists VARCHAR(255) DEFAULT NULL, remark TEXT DEFAULT NULL, is_private BOOLEAN NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA761220EA6 ON event (creator_id)');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(event_id, user_id))');
        $this->addSql('CREATE INDEX IDX_92589AE271F7E88B ON event_user (event_id)');
        $this->addSql('CREATE INDEX IDX_92589AE2A76ED395 ON event_user (user_id)');
        $this->addSql('CREATE TABLE event_comment (event_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(event_id, comment_id))');
        $this->addSql('CREATE INDEX IDX_1123FBC371F7E88B ON event_comment (event_id)');
        $this->addSql('CREATE INDEX IDX_1123FBC3F8697D13 ON event_comment (comment_id)');
        $this->addSql('CREATE TABLE gallery (id SERIAL NOT NULL, event_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_472B783A71F7E88B ON gallery (event_id)');
        $this->addSql('CREATE TABLE image (id SERIAL NOT NULL, gallery_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, filesize INT NOT NULL, metadata TEXT DEFAULT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045F4E7AF8F ON image (gallery_id)');
        $this->addSql('COMMENT ON COLUMN image.metadata IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE item (id SERIAL NOT NULL, item_list_id INT DEFAULT NULL, item_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F1B251E36F330DF ON item (item_list_id)');
        $this->addSql('CREATE TABLE item_comment (item_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(item_id, comment_id))');
        $this->addSql('CREATE INDEX IDX_9F297438126F525E ON item_comment (item_id)');
        $this->addSql('CREATE INDEX IDX_9F297438F8697D13 ON item_comment (comment_id)');
        $this->addSql('CREATE TABLE item_list (id SERIAL NOT NULL, list_name VARCHAR(255) NOT NULL, related_to VARCHAR(255) NOT NULL, related_to_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE item_list_comment (item_list_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(item_list_id, comment_id))');
        $this->addSql('CREATE INDEX IDX_876172A036F330DF ON item_list_comment (item_list_id)');
        $this->addSql('CREATE INDEX IDX_876172A0F8697D13 ON item_list_comment (comment_id)');
        $this->addSql('CREATE TABLE poll (id SERIAL NOT NULL, question VARCHAR(255) NOT NULL, is_multiple_choice BOOLEAN NOT NULL, is_anonymous BOOLEAN NOT NULL, related_to VARCHAR(255) NOT NULL, related_to_id INT NOT NULL, poll_type VARCHAR(255) NOT NULL, finished BOOLEAN NOT NULL, voting_start TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, voting_end TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE poll_option (id SERIAL NOT NULL, poll_id INT NOT NULL, name VARCHAR(255) NOT NULL, votes INT NOT NULL, winner BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B68343EB3C947C0F ON poll_option (poll_id)');
        $this->addSql('CREATE TABLE poll_option_user (poll_option_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(poll_option_id, user_id))');
        $this->addSql('CREATE INDEX IDX_57FF0BA86C13349B ON poll_option_user (poll_option_id)');
        $this->addSql('CREATE INDEX IDX_57FF0BA8A76ED395 ON poll_option_user (user_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A5F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA761220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_comment ADD CONSTRAINT FK_1123FBC371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_comment ADD CONSTRAINT FK_1123FBC3F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E36F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_comment ADD CONSTRAINT FK_9F297438126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_comment ADD CONSTRAINT FK_9F297438F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_list_comment ADD CONSTRAINT FK_876172A036F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_list_comment ADD CONSTRAINT FK_876172A0F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll_option ADD CONSTRAINT FK_B68343EB3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll_option_user ADD CONSTRAINT FK_57FF0BA86C13349B FOREIGN KEY (poll_option_id) REFERENCES poll_option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE poll_option_user ADD CONSTRAINT FK_57FF0BA8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment_user DROP CONSTRAINT FK_ABA574A5F8697D13');
        $this->addSql('ALTER TABLE comment_user DROP CONSTRAINT FK_ABA574A5A76ED395');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA761220EA6');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT FK_92589AE271F7E88B');
        $this->addSql('ALTER TABLE event_user DROP CONSTRAINT FK_92589AE2A76ED395');
        $this->addSql('ALTER TABLE event_comment DROP CONSTRAINT FK_1123FBC371F7E88B');
        $this->addSql('ALTER TABLE event_comment DROP CONSTRAINT FK_1123FBC3F8697D13');
        $this->addSql('ALTER TABLE gallery DROP CONSTRAINT FK_472B783A71F7E88B');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F4E7AF8F');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E36F330DF');
        $this->addSql('ALTER TABLE item_comment DROP CONSTRAINT FK_9F297438126F525E');
        $this->addSql('ALTER TABLE item_comment DROP CONSTRAINT FK_9F297438F8697D13');
        $this->addSql('ALTER TABLE item_list_comment DROP CONSTRAINT FK_876172A036F330DF');
        $this->addSql('ALTER TABLE item_list_comment DROP CONSTRAINT FK_876172A0F8697D13');
        $this->addSql('ALTER TABLE poll_option DROP CONSTRAINT FK_B68343EB3C947C0F');
        $this->addSql('ALTER TABLE poll_option_user DROP CONSTRAINT FK_57FF0BA86C13349B');
        $this->addSql('ALTER TABLE poll_option_user DROP CONSTRAINT FK_57FF0BA8A76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_user');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('DROP TABLE event_comment');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_comment');
        $this->addSql('DROP TABLE item_list');
        $this->addSql('DROP TABLE item_list_comment');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE poll_option');
        $this->addSql('DROP TABLE poll_option_user');
        $this->addSql('DROP TABLE "user"');
    }
}
