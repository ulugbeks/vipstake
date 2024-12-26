<?php

namespace App\Repository\Frontend;

use App\Entity\Frontend\NavItem;
use App\Trait\Frontend\FindIdsTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NavItem>
 *
 * @method NavItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method NavItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method NavItem[]    findAll()
 * @method NavItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavItemRepository extends ServiceEntityRepository
{
    use FindIdsTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavItem::class);
    }

    public function save(NavItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NavItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return NavItem[] Returns an array of NavItem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NavItem
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
