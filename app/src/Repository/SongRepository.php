<?php

namespace App\Repository;

use App\Entity\Band;
use App\Entity\Song;
use App\Enum\SongStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Song>
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    public function findByBand(Band $band, SongStatus $songStatus = null): array
    {
        $query = $this->createQueryBuilder('s')
            ->join('s.artist', 'a')
            ->where('a.band = :band')
            ->setParameter('band', $band)
            ->orderBy('s.title', 'ASC');

            if ($songStatus) {

            $query->andWhere('s.status = :search')
                ->setParameter('search', $songStatus);
            }



            return $query->getQuery()->getResult();
    }

    public function countByStatus(Band $band, bool $onlyActiveStatus = true): array {
        $query= $this->createQueryBuilder('s')
            ->select('s.status, COUNT(s.id) as total')
            ->join('s.artist', 'a')
            ->where('a.band = :band')
            ->setParameter('band', $band)
            ->groupBy('s.status');
            if ($onlyActiveStatus) {
                $query->andWhere('s.status IN (:statuses)')
                    ->setParameter('statuses', [
                        SongStatus::Learning,
                        SongStatus::Pending,
                        SongStatus::Validated,
                    ]);
            };
            return $query->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return Song[] Returns an array of Song objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Song
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
