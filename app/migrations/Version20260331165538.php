<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260331165538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, band_id INT NOT NULL, INDEX IDX_159968749ABEB17 (band_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE backing_track (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, file_name VARCHAR(150) NOT NULL, song_id INT NOT NULL, instrument_id INT NOT NULL, INDEX IDX_A4B444CAA0BDB2F3 (song_id), INDEX IDX_A4B444CACF11D9C (instrument_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE bands (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, is_active TINYINT NOT NULL, created_by_id INT NOT NULL, INDEX IDX_2C9E2B59B03A8386 (created_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE bands_members (id INT AUTO_INCREMENT NOT NULL, joined_at DATETIME NOT NULL, is_active TINYINT NOT NULL, roles JSON NOT NULL, band_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9566033F49ABEB17 (band_id), INDEX IDX_9566033FA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, starts_at DATETIME NOT NULL, ends_at DATETIME NOT NULL, is_all_day TINYINT NOT NULL, event_type_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_3BAE0AA7401B253C (event_type_id), INDEX IDX_3BAE0AA749ABEB17 (band_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE event_setlist_song (id INT AUTO_INCREMENT NOT NULL, position SMALLINT NOT NULL, song_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_8B19A5E6A0BDB2F3 (song_id), INDEX IDX_8B19A5E671F7E88B (event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE event_type (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE icon (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, file_name VARCHAR(150) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instrument (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, icon_id INT DEFAULT NULL, INDEX IDX_3CBF69DD54B9D732 (icon_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE setlist_model (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, band_id INT NOT NULL, INDEX IDX_1BCCE20D49ABEB17 (band_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE setlist_model_song (id INT AUTO_INCREMENT NOT NULL, position SMALLINT NOT NULL, setlist_model_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_DBAD77069003498D (setlist_model_id), INDEX IDX_DBAD7706A0BDB2F3 (song_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE song (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, duration SMALLINT DEFAULT NULL, bpm SMALLINT DEFAULT NULL, lyrics LONGTEXT DEFAULT NULL, artist_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_33EDEEA1B7970CF8 (artist_id), INDEX IDX_33EDEEA16BF700BD (status_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE song_link (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, link VARCHAR(255) NOT NULL, song_id INT NOT NULL, INDEX IDX_453F4F04A0BDB2F3 (song_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE song_status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE song_vote (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, song_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_29835391A0BDB2F3 (song_id), INDEX IDX_29835391A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, label VARCHAR(100) NOT NULL, details LONGTEXT DEFAULT NULL, executed_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, event_id INT DEFAULT NULL, INDEX IDX_723705D1A76ED395 (user_id), INDEX IDX_723705D171F7E88B (event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(320) NOT NULL, created_at DATETIME NOT NULL, is_active TINYINT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE artist ADD CONSTRAINT FK_159968749ABEB17 FOREIGN KEY (band_id) REFERENCES bands (id)');
        $this->addSql('ALTER TABLE backing_track ADD CONSTRAINT FK_A4B444CAA0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)');
        $this->addSql('ALTER TABLE backing_track ADD CONSTRAINT FK_A4B444CACF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('ALTER TABLE bands ADD CONSTRAINT FK_2C9E2B59B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE bands_members ADD CONSTRAINT FK_9566033F49ABEB17 FOREIGN KEY (band_id) REFERENCES bands (id)');
        $this->addSql('ALTER TABLE bands_members ADD CONSTRAINT FK_9566033FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA749ABEB17 FOREIGN KEY (band_id) REFERENCES bands (id)');
        $this->addSql('ALTER TABLE event_setlist_song ADD CONSTRAINT FK_8B19A5E6A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)');
        $this->addSql('ALTER TABLE event_setlist_song ADD CONSTRAINT FK_8B19A5E671F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DD54B9D732 FOREIGN KEY (icon_id) REFERENCES icon (id)');
        $this->addSql('ALTER TABLE setlist_model ADD CONSTRAINT FK_1BCCE20D49ABEB17 FOREIGN KEY (band_id) REFERENCES bands (id)');
        $this->addSql('ALTER TABLE setlist_model_song ADD CONSTRAINT FK_DBAD77069003498D FOREIGN KEY (setlist_model_id) REFERENCES setlist_model (id)');
        $this->addSql('ALTER TABLE setlist_model_song ADD CONSTRAINT FK_DBAD7706A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA1B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA16BF700BD FOREIGN KEY (status_id) REFERENCES song_status (id)');
        $this->addSql('ALTER TABLE song_link ADD CONSTRAINT FK_453F4F04A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)');
        $this->addSql('ALTER TABLE song_vote ADD CONSTRAINT FK_29835391A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)');
        $this->addSql('ALTER TABLE song_vote ADD CONSTRAINT FK_29835391A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist DROP FOREIGN KEY FK_159968749ABEB17');
        $this->addSql('ALTER TABLE backing_track DROP FOREIGN KEY FK_A4B444CAA0BDB2F3');
        $this->addSql('ALTER TABLE backing_track DROP FOREIGN KEY FK_A4B444CACF11D9C');
        $this->addSql('ALTER TABLE bands DROP FOREIGN KEY FK_2C9E2B59B03A8386');
        $this->addSql('ALTER TABLE bands_members DROP FOREIGN KEY FK_9566033F49ABEB17');
        $this->addSql('ALTER TABLE bands_members DROP FOREIGN KEY FK_9566033FA76ED395');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7401B253C');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA749ABEB17');
        $this->addSql('ALTER TABLE event_setlist_song DROP FOREIGN KEY FK_8B19A5E6A0BDB2F3');
        $this->addSql('ALTER TABLE event_setlist_song DROP FOREIGN KEY FK_8B19A5E671F7E88B');
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DD54B9D732');
        $this->addSql('ALTER TABLE setlist_model DROP FOREIGN KEY FK_1BCCE20D49ABEB17');
        $this->addSql('ALTER TABLE setlist_model_song DROP FOREIGN KEY FK_DBAD77069003498D');
        $this->addSql('ALTER TABLE setlist_model_song DROP FOREIGN KEY FK_DBAD7706A0BDB2F3');
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA1B7970CF8');
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA16BF700BD');
        $this->addSql('ALTER TABLE song_link DROP FOREIGN KEY FK_453F4F04A0BDB2F3');
        $this->addSql('ALTER TABLE song_vote DROP FOREIGN KEY FK_29835391A0BDB2F3');
        $this->addSql('ALTER TABLE song_vote DROP FOREIGN KEY FK_29835391A76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D171F7E88B');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE backing_track');
        $this->addSql('DROP TABLE bands');
        $this->addSql('DROP TABLE bands_members');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_setlist_song');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE icon');
        $this->addSql('DROP TABLE instrument');
        $this->addSql('DROP TABLE setlist_model');
        $this->addSql('DROP TABLE setlist_model_song');
        $this->addSql('DROP TABLE song');
        $this->addSql('DROP TABLE song_link');
        $this->addSql('DROP TABLE song_status');
        $this->addSql('DROP TABLE song_vote');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
