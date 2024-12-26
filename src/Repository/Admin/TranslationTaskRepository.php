<?php

namespace App\Repository\Admin;

use App\Entity\Admin\TranslationTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TranslationTask>
 *
 * @method TranslationTask|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslationTask|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslationTask[]    findAll()
 * @method TranslationTask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslationTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranslationTask::class);
    }

    public function save(TranslationTask $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TranslationTask $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByStatusQueryBuilder($status){
        return $this->createQueryBuilder('tt')
            ->where('tt.status = :status')
            ->setParameter('status', $status);
    }

    public function findAllQueryBuilder(){
        return $this->createQueryBuilder('tt');
    }

//    /**
//     * @return TranslationTask[] Returns an array of TranslationTask objects
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

//    public function findOneBySomeField($value): ?TranslationTask
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
