<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260331170157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artists (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, band_id INT NOT NULL, INDEX IDX_68D3801E49ABEB17 (band_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE backing_tracks (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, file_name VARCHAR(150) NOT NULL, song_id INT NOT NULL, instrument_id INT NOT NULL, INDEX IDX_601B50E1A0BDB2F3 (song_id), INDEX IDX_601B50E1CF11D9C (instrument_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE bands (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, is_active TINYINT NOT NULL, created_by_id INT NOT NULL, INDEX IDX_2C9E2B59B03A8386 (created_by_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE bands_members (id INT AUTO_INCREMENT NOT NULL, joined_at DATETIME NOT NULL, is_active TINYINT NOT NULL, roles JSON NOT NULL, band_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9566033F49ABEB17 (band_id), INDEX IDX_9566033FA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, starts_at DATETIME NOT NULL, ends_at DATETIME NOT NULL, is_all_day TINYINT NOT NULL, event_type_id INT NOT NULL, band_id INT NOT NULL, INDEX IDX_5387574A401B253C (event_type_id), INDEX IDX_5387574A49ABEB17 (band_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE events_setlists_songs (id INT AUTO_INCREMENT NOT NULL, position SMALLINT NOT NULL, song_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_1C0CD0B8A0BDB2F3 (song_id), INDEX IDX_1C0CD0B871F7E88B (event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE events_types (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE icons (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, file_name VARCHAR(150) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE instruments (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, icon_id INT DEFAULT NULL, INDEX IDX_E350DE0B54B9D732 (icon_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE setlist_models_songs (id INT AUTO_INCREMENT NOT NULL, position SMALLINT NOT NULL, setlist_model_id INT NOT NULL, song_id INT NOT NULL, INDEX IDX_505CE7619003498D (setlist_model_id), INDEX IDX_505CE761A0BDB2F3 (song_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE setlists_models (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, band_id INT NOT NULL, INDEX IDX_A667FF9549ABEB17 (band_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE songs (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, duration SMALLINT DEFAULT NULL, bpm SMALLINT DEFAULT NULL, lyrics LONGTEXT DEFAULT NULL, artist_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_BAECB19BB7970CF8 (artist_id), INDEX IDX_BAECB19B6BF700BD (status_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE songs_links (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, link VARCHAR(255) NOT NULL, song_id INT NOT NULL, INDEX IDX_55DC0FE6A0BDB2F3 (song_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE songs_status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE songs_votes (id INT AUTO_INCREMENT NOT NULL, status TINYINT NOT NULL, created_at DATETIME NOT NULL, song_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D5D5D431A0BDB2F3 (song_id), INDEX IDX_D5D5D431A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 2) NOT NULL, label VARCHAR(100) NOT NULL, details LONGTEXT DEFAULT NULL, executed_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, event_id INT DEFAULT NULL, INDEX IDX_EAA81A4CA76ED395 (user_id), INDEX IDX_EAA81A4C71F7E88B (event_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(320) NOT NULL, created_at DATETIME NOT NULL, is_active TINYINT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE artists ADD CONSTRAINT FK_68D3801E49ABEB17 FOREIGN KEY (band_id) REFERENCES bands (id)');
        $this->addSql('ALTER TABLE backing_tracks ADD CONSTRAINT FK_601B50E1A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id)');
        $this->addSql('ALTER TABLE backing_tracks ADD CONSTRAINT FK_601B50E1CF11D9C FOREIGN KEY (instrument_id) REFERENCES instruments (id)');
        $this->addSql('ALTER TABLE bands ADD CONSTRAINT FK_2C9E2B59B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE bands_members ADD CONSTRAINT FK_9566033F49ABEB17 FOREIGN KEY (band_id) REFERENCES bands (id)');
        $this->addSql('ALTER TABLE bands_members ADD CONSTRAINT FK_9566033FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A401B253C FOREIGN KEY (event_type_id) REFERENCES events_types (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A49ABEB17 FOREIGN KEY (band_id) REFERENCES bands (id)');
        $this->addSql('ALTER TABLE events_setlists_songs ADD CONSTRAINT FK_1C0CD0B8A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id)');
        $this->addSql('ALTER TABLE events_setlists_songs ADD CONSTRAINT FK_1C0CD0B871F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE instruments ADD CONSTRAINT FK_E350DE0B54B9D732 FOREIGN KEY (icon_id) REFERENCES icons (id)');
        $this->addSql('ALTER TABLE setlist_models_songs ADD CONSTRAINT FK_505CE7619003498D FOREIGN KEY (setlist_model_id) REFERENCES setlists_models (id)');
        $this->addSql('ALTER TABLE setlist_models_songs ADD CONSTRAINT FK_505CE761A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id)');
        $this->addSql('ALTER TABLE setlists_models ADD CONSTRAINT FK_A667FF9549ABEB17 FOREIGN KEY (band_id) REFERENCES bands (id)');
        $this->addSql('ALTER TABLE songs ADD CONSTRAINT FK_BAECB19BB7970CF8 FOREIGN KEY (artist_id) REFERENCES artists (id)');
        $this->addSql('ALTER TABLE songs ADD CONSTRAINT FK_BAECB19B6BF700BD FOREIGN KEY (status_id) REFERENCES songs_status (id)');
        $this->addSql('ALTER TABLE songs_links ADD CONSTRAINT FK_55DC0FE6A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id)');
        $this->addSql('ALTER TABLE songs_votes ADD CONSTRAINT FK_D5D5D431A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id)');
        $this->addSql('ALTER TABLE songs_votes ADD CONSTRAINT FK_D5D5D431A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C71F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artists DROP FOREIGN KEY FK_68D3801E49ABEB17');
        $this->addSql('ALTER TABLE backing_tracks DROP FOREIGN KEY FK_601B50E1A0BDB2F3');
        $this->addSql('ALTER TABLE backing_tracks DROP FOREIGN KEY FK_601B50E1CF11D9C');
        $this->addSql('ALTER TABLE bands DROP FOREIGN KEY FK_2C9E2B59B03A8386');
        $this->addSql('ALTER TABLE bands_members DROP FOREIGN KEY FK_9566033F49ABEB17');
        $this->addSql('ALTER TABLE bands_members DROP FOREIGN KEY FK_9566033FA76ED395');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A401B253C');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A49ABEB17');
        $this->addSql('ALTER TABLE events_setlists_songs DROP FOREIGN KEY FK_1C0CD0B8A0BDB2F3');
        $this->addSql('ALTER TABLE events_setlists_songs DROP FOREIGN KEY FK_1C0CD0B871F7E88B');
        $this->addSql('ALTER TABLE instruments DROP FOREIGN KEY FK_E350DE0B54B9D732');
        $this->addSql('ALTER TABLE setlist_models_songs DROP FOREIGN KEY FK_505CE7619003498D');
        $this->addSql('ALTER TABLE setlist_models_songs DROP FOREIGN KEY FK_505CE761A0BDB2F3');
        $this->addSql('ALTER TABLE setlists_models DROP FOREIGN KEY FK_A667FF9549ABEB17');
        $this->addSql('ALTER TABLE songs DROP FOREIGN KEY FK_BAECB19BB7970CF8');
        $this->addSql('ALTER TABLE songs DROP FOREIGN KEY FK_BAECB19B6BF700BD');
        $this->addSql('ALTER TABLE songs_links DROP FOREIGN KEY FK_55DC0FE6A0BDB2F3');
        $this->addSql('ALTER TABLE songs_votes DROP FOREIGN KEY FK_D5D5D431A0BDB2F3');
        $this->addSql('ALTER TABLE songs_votes DROP FOREIGN KEY FK_D5D5D431A76ED395');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CA76ED395');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C71F7E88B');
        $this->addSql('DROP TABLE artists');
        $this->addSql('DROP TABLE backing_tracks');
        $this->addSql('DROP TABLE bands');
        $this->addSql('DROP TABLE bands_members');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE events_setlists_songs');
        $this->addSql('DROP TABLE events_types');
        $this->addSql('DROP TABLE icons');
        $this->addSql('DROP TABLE instruments');
        $this->addSql('DROP TABLE setlist_models_songs');
        $this->addSql('DROP TABLE setlists_models');
        $this->addSql('DROP TABLE songs');
        $this->addSql('DROP TABLE songs_links');
        $this->addSql('DROP TABLE songs_status');
        $this->addSql('DROP TABLE songs_votes');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
