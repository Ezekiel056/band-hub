<?php
// src/Enum/BandStatus.php
namespace App\Enum;

enum AppMenuTabs: string
{
    case Home   = 'home';
    case Repertoire = 'repertoire';
    case Setlists  = 'setlilsts';
    case Events = 'events';
    case Finances = 'finances';

    case Settings = 'settings';


}
