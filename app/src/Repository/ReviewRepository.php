<?php

namespace App\Repository;

use App\Document\Review;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class ReviewRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review[]
     */
    public function findLatest(int $limit = 3): array
    {
        return $this->createQueryBuilder()
            ->sort('createdAt', 'desc')
            ->limit($limit)
            ->getQuery()
            ->execute()
            ->toArray();
    }

    public function findOneByUserId(int $userId): ?Review
    {
        return $this->findOneBy(['userId' => $userId]);
    }
}