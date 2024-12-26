<?php

namespace App\Repository\Frontend;

use App\Entity\Frontend\VipReview;
use App\Interface\StatusInterface;
use App\Trait\Frontend\FindIdsTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VipReview>
 *
 * @method VipReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method VipReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method VipReview[]    findAll()
 * @method VipReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VipReviewRepository extends ServiceEntityRepository
{
    use FindIdsTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VipReview::class);
    }

    public function save(VipReview $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VipReview $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllIndexable()
    {
        return $this->createQueryBuilder('q')
            ->where('q.status = :status')
            ->andWhere('q.createdAt < :date')
            ->setParameter('status', StatusInterface::PUBLISH)
            ->setParameter('date', new \DateTime('now', new \DateTimeZone('UTC')), Types::DATETIME_MUTABLE)
            ->getQuery()
            ->getResult();
    }

    public function findAllByRatingQueryBuilder()
    {
        return $this->createQueryBuilder('q')
            ->where('q.status = :status')
            ->andWhere('q.createdAt < :date')
            ->setParameter('status', StatusInterface::PUBLISH)
            ->setParameter('date', new \DateTime('now', new \DateTimeZone('UTC')), Types::DATETIME_MUTABLE)
            ->orderBy('q.rating', 'DESC')
            ->getQuery()
            ;

    }
}
