<?php
// src/Enum/BandStatus.php
namespace App\Enum;

enum SongStatus: string
{
    case Pending   = 'pending';
    case Learning = 'learning';
    case Validated  = 'validated';
    case Rejected = 'rejected';
    case Archived = 'archived';

    // Icone du status
    public function icon(): string
    {
        return match($this) {
            self::Pending   => 'icon-vote',
            self::Validated => 'icon-success',
            self::Learning  => 'icon-flash',
        };
    }

    // Badge du status
    public function tagClass(): string
    {
        return match($this) {
            self::Pending     => 'song-status-tag-pending',
            self::Validated   => 'song-status-tag-validated',
            self::Learning    => 'song-status-tag-learning',
            self::Rejected    => 'song-status-tag-rejected',
            self::Archived    => 'song-status-tag-archived',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Pending     => 'À voter',
            self::Validated   => 'Validé',
            self::Learning    => 'À apprendre',
            self::Rejected    => 'Rejeté',
            self::Archived    => 'Archivé',
        };
    }
}


