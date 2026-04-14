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

    public function icon(): string
    {
        return match($this) {
            self::Pending   => 'icon-vote',
            self::Validated => 'icon-success',
            self::Learning  => 'icon-flash',
        };
    }
}


