<?php

namespace App\Controller\Admin;

use App\Attribute\Admin\Editable;
use App\Attribute\Admin\Nav;
use App\Entity\Frontend\NavItem;
use App\Entity\Frontend\NavMenu;
use App\Form\NavMenuType;
use App\Helpers\Helper;
use App\Repository\Admin\MetaRepository;
use App\Repository\Frontend\NavItemRepository;
use App\Repository\Frontend\NavMenuRepository;
use App\Service\Admin\FieldsService;
use App\Service\Admin\NavItemService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NavController extends AdminController
{
    private NavItemRepository $repository;

    public function __construct(
        EntityManagerInterface $em,
        NavItemRepository $repository,
        MetaRepository $metaRepository,
        FieldsService $fieldsService
    ) {
        parent::__construct($em, $metaRepository,$fieldsService);
        $this->repository = $repository;
    }

    #[Route('/admin/nav', name: 'admin_nav')]
    #[Nav(icon: '/admin/img/nav.svg', name: 'Navigation', order: '4')]
    public function index(NavMenuRepository $repository): Response
    {
        return $this->render('admin/nav/index.html.twig', [
            'users' => $repository->findAll(),
            'pageName' => 'Nav Menus',
        ]);
    }

    #[Route('/admin/nav/add', name: 'admin_nav_add')]
    #[Editable(NavMenu::class, Editable::ACTION_ADD)]
    public function add(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(NavMenuType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var NavMenu $navMenu */
            $navMenu = $form->getData();
            $navItems = $request->get('nav');
            $navItems = Helper::arrayTree($navItems);
            $em->persist($navMenu);

            $this->createItems($navItems, $items);

            foreach ($items as $item) {
                $item->setNavMenu($navMenu);
                $em->persist($item);
            }

            $em->flush();

            return $this->redirectToRoute('admin_nav_edit', ['id' => $navMenu->getId()]);
        }

        return $this->render('admin/nav/item.html.twig', [
            'navForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/nav/edit/{id}', name: 'admin_nav_edit')]
    #[Editable(NavMenu::class, Editable::ACTION_EDIT)]
    public function edit(NavMenu $navMenu, Request $request, EntityManagerInterface $em, NavItemService $service)
    {
        $form = $this->createForm(NavMenuType::class, $navMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var NavMenu $navMenu */
            $navMenu = $form->getData();
            $navItems = $request->get('nav');

            if ($navItems) {
                $navItems = Helper::arrayTree($navItems);
                $this->createItems($navItems, $items);
                $this->clearNavMenu($navMenu, $items);
                $this->setItems($items, $navMenu);
            } else {
                $this->clearNavMenu($navMenu, []);
            }

            $em->flush();

            return $this->redirectToRoute('admin_nav_edit', ['id' => $navMenu->getId()]);
        }

        $navItems = NavItemService::setIndexes($navMenu->getItems());

        return $this->render('admin/nav/item.html.twig', [
            'navForm' => $form->createView(),
            'items' => $navItems,
        ]);
    }

    /**
     * @param $navItems
     * @param NavItem[] $items
     * @param $parent
     * @return mixed
     */
    private function createItems($navItems, &$items, $parent = null)
    {
        foreach ($navItems as $item) {
            $navItem = isset($item['real_id']) ? $this->repository->find($item['real_id']) : new NavItem();
            $navItem->setParentItem($parent);
            $navItem->setName($item['title']);
            if (isset($item['url'])) {
                $navItem->setUrl($item['url']);
            }
            if (isset($item['id'])) {
                $navItem->setEntityId($item['id']);
            }
            $navItem->setPosition($item['position']);
            $navItem->setEntityType($item['type']);

            if (isset($item['children'])) {
                $this->createItems($item['children'], $items, $navItem);
            }

            $items[] = $navItem;
        }


        return $items;
    }

    private function clearNavMenu(NavMenu $navMenu, $items)
    {
        foreach ($navMenu->getItems() as $item) {
            if (!in_array($item, $items)) {
                $navMenu->removeItem($item);
            }
        }
    }

    private function setItems($items, $navMenu)
    {
        foreach ($items as $item) {
            $item->setNavMenu($navMenu);
                $item->getId() ?? $this->em->persist($item);
        }
    }
}