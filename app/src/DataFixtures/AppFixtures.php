<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\BackingTrack;
use App\Entity\Band;
use App\Entity\BandMember;
use App\Entity\Instrument;
use App\Entity\Song;
use App\Entity\SongStatus;
use App\Entity\User;
use App\Repository\SongStatusRepository;
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

        $user = new User();
        $user->setIsActive(true);
        $user->setEmail('user@test.com');
        $user->setUsername('ezekiel');
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

        $statusMap = [
            'proposé' => 'propose',
            'en cours' => 'en_cours',
            'validé' => 'valide',
            'refusé' => 'refuse',
            'archivé' => 'archive',
        ];
        foreach ($statusMap as $label => $ref) {
            $data = new SongStatus();
            $data->setLabel($label);
            $manager->persist($data);
            $this->addReference('status_'. $ref, $data);
        }






        $song = new Song();
        $song->setArtist($artist);
        $song->setTitle('Time is running out');
        $song->setBpm(145);
        $song->setDuration(220);
        $song->setStatus($this->getReference('status_valide', SongStatus::class));
        $song->setLyrics(simplexml_load_file('http://www.lipsum.com/feed/xml?amount=8&what=paras&start=0')->lipsum);

        $manager->persist($song);



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
            ['instrument' => 'guitar', 'label' => 'Guitare - Time is running out'],
            ['instrument' => 'basse', 'label' => 'Basse - Time is running out'],
            ['instrument' => 'drums', 'label' => 'Batterie - Time is running out'],
            ['instrument' => 'vocals', 'label' => 'Voix - Time is running out'],
            ['instrument' => 'piano', 'label' => 'Piano - Time is running out'],
        ];

        foreach ($backingTracks as $trackData) {
            $track = new BackingTrack();
            $track->setSong($song);
            $track->setInstrument($this->getReference('instrument_' . $trackData['instrument'], Instrument::class));
            $track->setLabel($trackData['label']);
            $track->setFileName('1-time-is-running-out-' . $trackData['instrument'] . '.mp3');
            $track->setDescription(null);
            $manager->persist($track);
        }



        $manager->flush();
    }
}
