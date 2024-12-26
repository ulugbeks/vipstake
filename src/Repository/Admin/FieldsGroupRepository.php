<?php

namespace App\Repository\Admin;

use App\Entity\Admin\FieldsGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FieldsGroup>
 *
 * @method FieldsGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldsGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldsGroup[]    findAll()
 * @method FieldsGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldsGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldsGroup::class);
    }

    public function save(FieldsGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FieldsGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return FieldsGroup[] Returns an array of FieldsGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FieldsGroup
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
