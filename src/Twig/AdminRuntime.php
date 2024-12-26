<?php

namespace App\Twig;

use App\Attribute\Admin\Editable;
use App\Attribute\Admin\Nav;
use App\Entity\Admin\FieldsGroup;
use App\Entity\Admin\Option;
use App\Entity\Frontend\NavItem;
use App\Entity\Frontend\Page;
use App\Service\Admin\EntityActionsService;
use App\Service\Admin\NavItemService;
use App\Service\Admin\NavManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\RuntimeExtensionInterface;

class AdminRuntime implements RuntimeExtensionInterface
{
    private ParameterBagInterface $container;
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;

    private NavItemService $navItemService;

    public function __construct(
        ParameterBagInterface $container,
        EntityManagerInterface $em,
        TokenStorageInterface $storage,
        NavItemService $navItemService,
    ) {
        $this->container = $container;
        $this->em = $em;
        $this->tokenStorage = $storage;
        $this->navItemService = $navItemService;
    }

    /**
     * @return Nav[]
     * @throws \ReflectionException
     */
    public function getSideNav()
    {
        return (new NavManager($this->tokenStorage))->getNav();
    }

    public function getEditUrl($entity)
    {
        $class = $this->em->getClassMetadata(get_class($entity))->name;

        $actionService = new EntityActionsService();

        return $actionService->getUrl($entity, Editable::ACTION_EDIT, $class);
    }

    public function getDeleteUrl($entity)
    {
        $class = $this->em->getClassMetadata(get_class($entity))->name;

        $actionService = new EntityActionsService();

        return $actionService->getUrl($entity, Editable::ACTION_DELETE, $class);
    }

    public function getRecoverUrl($entity)
    {
        $class = $this->em->getClassMetadata(get_class($entity))->name;

        $actionService = new EntityActionsService();

        return $actionService->getUrl($entity, Editable::ACTION_RECOVER, $class);
    }

    public function getUploadedAsset($asset)
    {
        $path = $this->container->get('uploads_media_path').$asset;

        return $path;
    }

    public function getFieldGroup($id)
    {
        return $this->em->getRepository(FieldsGroup::class)->find($id);
    }

    public function getNavItemRoute(NavItem $navItem, Request $request)
    {

        return $this->navItemService->generateRoute($navItem, $request);
    }

    public function debug($var)
    {
        dump($var);
    }

    public function isOptionTemplateExists($slug)
    {
        return file_exists(
            $this->container->get('kernel.project_dir').'/templates/admin/settings/options/'.$slug.'.html.twig'
        );
    }

    public function getPages()
    {
        return $this->em->getRepository(Page::class)->findAll();
    }

    public function getOptionLabel($slug): mixed
    {
        return $this->em->getRepository(Option::class)->findOneBy(['slug' => $slug])?->getLabel();
    }

    public function fixChoiceValue($group_label, $value)
    {
        return str_replace($group_label.' ', '', $value);
    }

    public function getIds($array)
    {
        $ids = [];

        if (!empty($array)) {
            foreach ($array as $item) {
                if (is_int($item)) {
                    $ids[] = $item;
                } else {
                    $ids[] = $item->getId();
                }
            }
        }

        return $ids;
    }

    public function transformStringSlug(string $slug)
    {
        $res = '';
        $chunks = explode('|', $slug);
        foreach ($chunks as $chunk) {
            $res .= "[$chunk]";
        }

        return $res;
    }

    public function decode($string){
        return json_decode($string, true);
    }
}