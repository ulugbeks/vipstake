<?php

namespace App\Repository\Frontend;

use App\Entity\Frontend\NavMenu;
use App\Trait\Frontend\FindIdsTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NavMenu>
 *
 * @method NavMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method NavMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method NavMenu[]    findAll()
 * @method NavMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavMenuRepository extends ServiceEntityRepository
{
    use FindIdsTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavMenu::class);
    }

    public function save(NavMenu $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NavMenu $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return NavMenu[] Returns an array of NavMenu objects
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

//    public function findOneBySomeField($value): ?NavMenu
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
