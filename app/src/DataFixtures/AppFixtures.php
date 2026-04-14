<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\BackingTrack;
use App\Entity\Band;
use App\Entity\BandMember;
use App\Entity\Instrument;
use App\Entity\Song;
use App\Entity\User;
use App\Enum\SongStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $insturmentsMap = [
            'guitare' => 'guitar',
            'basse' => 'basse',
            'batterie' => 'drums',
            'voix' => 'vocals',
            'piano' => 'piano',
        ];
        foreach ($insturmentsMap as $name => $ref) {
            $instrument = new Instrument();
            $instrument->setName($name);

            $manager->persist($instrument);
            $this->addReference('instrument_'. $ref, $instrument);
        }

        $backingTracks = [
            ['instrument' => 'guitar', 'label' => 'Guitare'],
            ['instrument' => 'basse', 'label' => 'Basse'],
            ['instrument' => 'drums', 'label' => 'Batterie'],
            ['instrument' => 'vocals', 'label' => 'Voix'],
            ['instrument' => 'piano', 'label' => 'Piano'],
        ];




        $user = new User();
        $user->setIsActive(true);
        $user->setEmail('user@test.com');
        $user->setUsername('ezekiel056');
        $user->setPassword($this->passwordHasher->hashPassword($user, '123456'));

        $manager->persist($user);

        $band = new Band();
        $band->setName('Band');
        $band->setIsActive(true);
        $band->setCreatedBy($user);

        $manager->persist($band);

        $bandMember = new BandMember();
        $bandMember->setBand($band);
        $bandMember->setUser($user);
        $bandMember->setIsActive(true);
        $bandMember->setJoinedAt(new \DateTimeImmutable());
        $bandMember->setRoles(['ROLE_USER','ROLE_ADMIN']);

        $manager->persist($bandMember);

        $artist = new Artist();
        $artist->setName('Muse');
        $artist->setBand($band);

        $manager->persist($artist);
        $this->addReference('artist_muse',$artist);


        $artist = new Artist();
        $artist->setName('System of a down');
        $artist->setBand($band);

        $manager->persist($artist);
        $this->addReference('artist_soad',$artist);


        $artist = new Artist();
        $artist->setName('Linking park');
        $artist->setBand($band);

        $manager->persist($artist);
        $this->addReference('artist_linkin_park',$artist);


        $artist = new Artist();
        $artist->setName('Foo fighters');
        $artist->setBand($band);

        $manager->persist($artist);
        $this->addReference('artist_foo_fighters',$artist);


        $artist = new Artist();
        $artist->setName('Green day');
        $artist->setBand($band);

        $manager->persist($artist);
        $this->addReference('artist_green_day',$artist);



        $songsData = [
            'artist_muse' => [
                ['title' => 'Hysteria', 'bpm' => 100, 'duration' => 225, 'status' => SongStatus::Validated],
                ['title' => 'Supermassive Black Hole', 'bpm' => 138, 'duration' => 208, 'status' => SongStatus::Validated],
                ['title' => 'Time Is Running Out', 'bpm' => 145, 'duration' => 236, 'status' => SongStatus::Learning],
            ],
            'artist_soad' => [
                ['title' => 'Aerials', 'bpm' => 79, 'duration' => 252, 'status' => SongStatus::Validated],
                ['title' => 'Chop Suey!', 'bpm' => 214, 'duration' => 210, 'status' => SongStatus::Pending],
                ['title' => 'Toxicity', 'bpm' => 126, 'duration' => 219, 'status' => SongStatus::Learning],
            ],
            'artist_linkin_park' => [
                ['title' => 'Numb', 'bpm' => 120, 'duration' => 185, 'status' => SongStatus::Validated],
                ['title' => 'In the End', 'bpm' => 105, 'duration' => 216, 'status' => SongStatus::Learning],
                ['title' => 'Crawling', 'bpm' => 116, 'duration' => 209, 'status' => SongStatus::Pending],
            ],
            'artist_foo_fighters' => [
                ['title' => 'Best of You', 'bpm' => 130, 'duration' => 256, 'status' => SongStatus::Validated],
                ['title' => 'Everlong', 'bpm' => 158, 'duration' => 250, 'status' => SongStatus::Validated],
                ['title' => 'The Pretender', 'bpm' => 173, 'duration' => 269, 'status' => SongStatus::Learning],
            ],
            'artist_green_day' => [
                ['title' => 'Boulevard of Broken Dreams', 'bpm' => 85, 'duration' => 260, 'status' => SongStatus::Validated],
                ['title' => 'American Idiot', 'bpm' => 176, 'duration' => 174, 'status' => SongStatus::Pending],
                ['title' => 'Wake Me Up When September Ends', 'bpm' => 104, 'duration' => 285, 'status' => SongStatus::Learning],
            ],
        ];

        foreach ($songsData as $artistRef => $songs) {
            foreach ($songs as $songData) {
                $song = new Song();
                $song->setArtist($this->getReference($artistRef, Artist::class));
                $song->setTitle($songData['title']);
                $song->setBpm($songData['bpm']);
                $song->setDuration($songData['duration']);
                $song->setStatus($songData['status']);
                $song->setLyrics('Lorem ipsum dolor sit amet consectetur adipisicing elit.');

                $manager->persist($song);

                foreach ($backingTracks as $trackData) {
                    $track = new BackingTrack();
                    $track->setSong($song);
                    $track->setInstrument($this->getReference('instrument_' . $trackData['instrument'], Instrument::class));
                    $track->setLabel($trackData['label']);
                    $track->setFileName(strtolower(str_replace(' ', '-', $songData['title'])) . '-' . $trackData['instrument'] . '.mp3');
                    $track->setDescription(null);
                    $manager->persist($track);
                }
            }
        }




        $manager->flush();
    }
}
