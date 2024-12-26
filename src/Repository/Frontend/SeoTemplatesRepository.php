<?php

namespace App\Repository\Frontend;

use App\Entity\Frontend\SeoTemplates;
use App\Trait\Frontend\FindIdsTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SeoTemplates>
 *
 * @method SeoTemplates|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeoTemplates|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeoTemplates[]    findAll()
 * @method SeoTemplates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeoTemplatesRepository extends ServiceEntityRepository
{
    use FindIdsTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeoTemplates::class);
    }

    public function save(SeoTemplates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SeoTemplates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SeoTemplates[] Returns an array of SeoTemplates objects
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

//    public function findOneBySomeField($value): ?SeoTemplates
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
