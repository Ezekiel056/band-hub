<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260421150003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backing_tracks DROP FOREIGN KEY `FK_601B50E1A0BDB2F3`');
        $this->addSql('ALTER TABLE backing_tracks ADD CONSTRAINT FK_601B50E1A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_setlists_songs DROP FOREIGN KEY `FK_1C0CD0B8A0BDB2F3`');
        $this->addSql('ALTER TABLE events_setlists_songs ADD CONSTRAINT FK_1C0CD0B8A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setlist_models_songs DROP FOREIGN KEY `FK_505CE761A0BDB2F3`');
        $this->addSql('ALTER TABLE setlist_models_songs ADD CONSTRAINT FK_505CE761A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE songs_links DROP FOREIGN KEY `FK_55DC0FE6A0BDB2F3`');
        $this->addSql('ALTER TABLE songs_links ADD CONSTRAINT FK_55DC0FE6A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE songs_votes DROP FOREIGN KEY `FK_D5D5D431A0BDB2F3`');
        $this->addSql('ALTER TABLE songs_votes ADD CONSTRAINT FK_D5D5D431A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE backing_tracks DROP FOREIGN KEY FK_601B50E1A0BDB2F3');
        $this->addSql('ALTER TABLE backing_tracks ADD CONSTRAINT `FK_601B50E1A0BDB2F3` FOREIGN KEY (song_id) REFERENCES songs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE events_setlists_songs DROP FOREIGN KEY FK_1C0CD0B8A0BDB2F3');
        $this->addSql('ALTER TABLE events_setlists_songs ADD CONSTRAINT `FK_1C0CD0B8A0BDB2F3` FOREIGN KEY (song_id) REFERENCES songs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE setlist_models_songs DROP FOREIGN KEY FK_505CE761A0BDB2F3');
        $this->addSql('ALTER TABLE setlist_models_songs ADD CONSTRAINT `FK_505CE761A0BDB2F3` FOREIGN KEY (song_id) REFERENCES songs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE songs_links DROP FOREIGN KEY FK_55DC0FE6A0BDB2F3');
        $this->addSql('ALTER TABLE songs_links ADD CONSTRAINT `FK_55DC0FE6A0BDB2F3` FOREIGN KEY (song_id) REFERENCES songs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE songs_votes DROP FOREIGN KEY FK_D5D5D431A0BDB2F3');
        $this->addSql('ALTER TABLE songs_votes ADD CONSTRAINT `FK_D5D5D431A0BDB2F3` FOREIGN KEY (song_id) REFERENCES songs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
