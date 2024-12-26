<?php

namespace App\Repository\Admin;

use App\Entity\Admin\TranslationError;
use App\Trait\Frontend\FindIdsTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TranslationError>
 *
 * @method TranslationError|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationError|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationError[]    findAll()
 * @method TranslationError[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationErrorRepository extends ServiceEntityRepository
{
    use FindIdsTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranslationError::class);
    }

    public function save(TranslationError $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TranslationError $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TranslationError[] Returns an array of TranslationError objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TranslationError
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
