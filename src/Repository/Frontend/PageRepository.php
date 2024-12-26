<?php

namespace App\Repository\Frontend;

use App\Entity\Frontend\Page;
use App\Interface\StatusInterface;
use App\Trait\Admin\SoftDeletableTrait;
use App\Trait\Frontend\FindBySlugTrait;
use App\Trait\Frontend\FindIdsTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 *
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    use FindIdsTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function save(Page $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Page $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllIndexable($mainPage){
        return $this->createQueryBuilder('q')
            ->where('q.status = :status')
            ->andWhere('q.id != :id')
            ->andWhere('q.createdAt < :date')
            ->setParameter('status', StatusInterface::PUBLISH)
            ->setParameter('date', new \DateTime('now', new \DateTimeZone('UTC')), Types::DATETIME_MUTABLE)
            ->setParameter('id', $mainPage)
            ->getQuery()
            ->getResult()
            ;
    }
}
