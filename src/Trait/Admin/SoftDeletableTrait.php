<?php

namespace App\Trait\Admin;

trait SoftDeletableTrait
{
    function findAll(){
        return $this->findBy(array('deletedAt' => null));
    }

    public function getTrashed()
    {
        return $this->createQueryBuilder('q')
            ->where('q.deletedAt is not NULL')
            ->getQuery()
            ->getResult();
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);
        $criteria['deletedAt'] = null;
        return $persister->loadAll($criteria, $orderBy, $limit, $offset);
    }
}