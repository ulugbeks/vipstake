<?php

namespace App\Controller\Frontend;

use App\Entity\Admin\Meta;
use App\Entity\Admin\Option;
use App\Entity\Frontend\ModelService;
use App\Entity\Frontend\ProfileFilter;
use App\Service\Admin\FieldsService;
use App\Service\Frontend\RouteUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Knp\DoctrineBehaviors\EventSubscriber\SoftDeletableEventSubscriber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\CacheInterface;


class FrontendController extends AbstractController
{
    protected EntityManagerInterface $em;
    private FieldsService $fieldsService;

    public function __construct(
        EntityManagerInterface $em,
        FieldsService $fieldsService,
        protected CacheInterface $cache,
        protected readonly RouteUrlService $urlService
    ) {
        $this->em = $em;
        $this->fieldsService = $fieldsService;
    }

    public function getOption($slug)
    {
        $optionRepo = $this->em->getRepository(Option::class);

        return $optionRepo->findOneBy(['slug' => $slug])?->getValue();
    }

    public function getFields($entity)
    {
        $fields = [];
        $class = $this->em->getClassMetadata(get_class($entity))->name;
        $metaRepository = $this->em->getRepository(Meta::class);
        $meta = $metaRepository->findFieldsFrontend($class, $entity->getId());

        /** @var Meta $item */
        foreach ($meta as $item) {
            $fields[$item->getField()->getLabel()] = json_validate($item->getContent()) ? json_decode(
                $item->getContent(),
                true
            ) : $item->getContent();
        }


        return $fields;
    }

    public function getField($entity)
    {
        $metaRepository = $this->em->getRepository(Meta::class);
        $meta = $metaRepository->findFieldsFrontend(get_class($entity), $entity->getId());

        return $this->fieldsService->transformData($meta);
    }

    public function getPrefix($locale)
    {
        return $locale == $this->getParameter('default_locale') ? '_default' : '';
    }
}