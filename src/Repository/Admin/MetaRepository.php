<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Meta;
use App\Entity\Frontend\Country;
use App\Field\FieldItem;
use App\Field\GroupFieldType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meta>
 *
 * @method Meta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meta[]    findAll()
 * @method Meta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meta::class);
    }

    public function save(Meta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Meta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFields($entityType, $id)
    {
        return $this->createQueryBuilder('m')
            ->join('m.field', 'f')
            ->where('m.entityType = :type')
            ->andWhere('m.entityId = :id')
            ->setParameter('type', $entityType)
            ->setParameter('id', $id)
            ->orderBy('m.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findFieldsFrontend($entityType, $id)
    {
        return $this->createQueryBuilder('m')
            ->join('m.field', 'f')
            ->where('m.entityType = :type')
            ->andWhere('m.entityId = :id')
            ->andWhere('f.parent IS NULL')
            ->setParameter('type', $entityType)
            ->setParameter('id', $id)
            ->orderBy('m.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findField($entityType, $id, $label)
    {
        $fields = [];

        try {
            /** @var Meta $res */
            $res = $this->createQueryBuilder('m')
                ->join('m.field', 'f')
                ->where('f.label = :label')
                ->andWhere('m.entityType = :type')
                ->andWhere('m.entityId = :id')
                ->setParameter('label', $label)
                ->setParameter('type', $entityType)
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleResult();

            return $res;
        } catch (\Exception $exception) {
            return $fields;
        }

    }


    public function findDuplicates($entityType, $id, $label)
    {
        $res = $this->createQueryBuilder('m')
            ->join('m.field', 'f')
            ->where('f.label = :label')
            ->andWhere('m.entityType = :type')
            ->andWhere('m.entityId = :id')
            ->setParameter('label', $label)
            ->setParameter('type', $entityType)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        /** @var Meta $duplicate */
        foreach ($res as $duplicate) {
            $this->getEntityManager()->remove($duplicate);

            $related = $duplicate->getField()->getRelated();
        }

        $this->getEntityManager()->flush();
    }

    public function getField($entityType, $id, $label)
    {
        $fields = [];

        try {
            /** @var Meta $res */
            $res = $this->createQueryBuilder('m')
                ->join('m.field', 'f')
                ->where('f.label = :label')
                ->andWhere('m.entityType = :type')
                ->andWhere('m.entityId = :id')
                ->setParameter('label', $label)
                ->setParameter('type', $entityType)
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleResult();

            $field = new FieldItem($res->getField());

            if ($field->getVar('isContainer')) {
                $this->parseField($field, $fields, $entityType, $id);
            } else {
                $fields[] = $res;
            }

            return $fields;
        } catch (\Exception $exception) {
            return $fields;
        }

    }

    private function parseField(FieldItem $field, &$fields, $entityType, $id)
    {
        $children = $this->findChildFields($field->getField()->getId(), $entityType, $id);

        /** @var Meta $meta */
        foreach ($children as $meta) {
            $field = new FieldItem($meta->getField());
            if ($field->getVar('isContainer')) {
                $this->parseField($field, $fields, $entityType, $id);
            } else {
                $fields[] = $meta;
            }
        }
    }

    private function findChildFields($parentId, $entityType, $id)
    {
        return $this->createQueryBuilder('m')
            ->join('m.field', 'f')
            ->where('f.parent = :parent_id')
            ->andWhere('m.entityType = :type')
            ->andWhere('m.entityId = :id')
            ->setParameter('parent_id', $parentId)
            ->setParameter('type', $entityType)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


    public function delete($fieldId, $entityId, $entityType)
    {
        $this->createQueryBuilder('m')
            ->delete()
            ->where('m.entityId = :entity_id')
            ->andWhere('m.entityType = :entity_type')
            ->andWhere('m.field = :field_id')
            ->setParameter('entity_id', $entityId)
            ->setParameter('entity_type', $entityType)
            ->setParameter('field_id', $fieldId)
            ->getQuery()
            ->getResult();
    }

    public function findTrash(array $fieldsId, $entityType)
    {
        return $this->createQueryBuilder('m')
            ->where('m.field IN (:fields)')
            ->andWhere('m.entityType = :entity')
            ->setParameter('fields', $fieldsId)
            ->setParameter('entity', $entityType)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Meta[] Returns an array of Meta objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Meta
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    private function collectFields(FieldItem $fieldItem)
    {

    }

    public function findEntityFaq(string $entity, array $ids)
    {
        return $this->createQueryBuilder('m')
            ->join('m.field', 'f')
            ->join('m.translations', 'mt')
            ->where('m.entityType = :entity_type')
            ->andWhere('m.entityId IN (:ids)')
            ->andWhere('f.label IN (:labels)')
            ->setParameter('labels', ['question', 'answer'])
            ->setParameter('entity_type', $entity)
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function findEntityMetas(string $entity, array $ids, array $fields)
    {
        return $this->createQueryBuilder('m')
            ->join('m.field', 'f')
            ->join('m.translations', 'mt')
            ->where('m.entityType = :entity_type')
            ->andWhere('m.entityId IN (:ids)')
            ->andWhere('f.label IN (:labels)')
            ->setParameter('labels', $fields)
            ->setParameter('entity_type', $entity)
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function getCustomizedCountries()
    {
        return $this->createQueryBuilder('m')
            ->join('m.field', 'f')
            ->join('m.translations', 'mt')
            ->where('m.entityType = :entity_type')
            ->andWhere('f.label IN (:labels)')
            ->andWhere('mt.content LIKE :content')
            ->setParameter('labels', ['content'])
            ->setParameter('entity_type', Country::class)
            ->setParameter('content','%href%')
            ->getQuery()
            ->getResult();
    }
}
