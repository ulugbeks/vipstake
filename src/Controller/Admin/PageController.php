<?php

namespace App\Controller\Admin;

use App\Attribute\Admin\Editable;
use App\Attribute\Admin\Nav;
use App\Entity\Frontend\Page;
use App\Form\PageType;
use App\Handlers\UploadHandler;
use App\Repository\Admin\MetaRepository;
use App\Repository\Frontend\PageRepository;
use App\Service\Admin\FieldsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AdminController
{
    #[Route('/admin/page', name: 'admin_page')]
    #[Nav(icon: '/admin/img/page.svg', name: 'Pages', order: '1')]
    public function index(PageRepository $repository): Response
    {
        return $this->render('admin/page/index.html.twig', [
            'pages' => $repository->findAll(),
            'pageName' => 'Pages'
        ]);
    }

    #[Route('/admin/page/add', name: 'admin_page_add')]
    #[Editable(Page::class, Editable::ACTION_ADD)]
    public function add(Request $request, EntityManagerInterface $em, UploadHandler $uploadHandler)
    {
        $form = $this->createForm(PageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Page $page */
            $page = $form->getData();
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['thumbnail']->getData();

            if ($uploadedFile) {
                $thumb = $uploadHandler->uploadImage($uploadedFile);
                $page->setThumbnail($thumb);
            }

            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
        }

        return $this->render('admin/page/item.html.twig', [
            'pageForm' => $form->createView(),
        ]);
    }

    #[Route('/admin/page/edit/{id}', name: 'admin_page_edit')]
    #[Editable(Page::class, Editable::ACTION_EDIT)]
    public function edit(Page $page, Request $request, EntityManagerInterface $em, FieldsService $fieldsService, MetaRepository $metaRepository, UploadHandler $uploadHandler)
    {
        $form = $this->createForm(PageType::class, $page);

        $fieldsForm = $this->createFieldsForm($page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Page $page */
            $page = $form->getData();
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['thumbnail']->getData();

            if ($uploadedFile) {
                $thumb = $uploadHandler->uploadImage($uploadedFile);
                $page->setThumbnail($thumb);
            }

            $meta = $request->get('meta');

            if ($meta) {
              $this->handleFields($fieldsForm, $meta, $page);
            }

            $em->flush();

            return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
        }

        return $this->render('admin/page/item.html.twig', [
            'pageForm' => $form->createView(),
            'fields' => $fieldsForm?->createView()
        ]);
    }

    #[Route('/admin/page/delete/{id}', name: 'admin_page_delete')]
    #[Editable(Page::class, Editable::ACTION_DELETE)]
    public function delete(Page $page, EntityManagerInterface $em)
    {
        $em->remove($page);
        $em->flush();

        return $this->redirectToRoute('admin_page');
    }

    #[Route('/admin/page/recover/{id}', name: 'admin_page_recover')]
    #[Editable(Page::class, Editable::ACTION_RECOVER)]
    public function recover(Page $page, EntityManagerInterface $em)
    {
        $page->setDeletedAt(null);
        $em->flush();

        return $this->redirectToRoute('admin_page');
    }

    #[Route('/admin/page/archive', name: 'admin_page_archive')]
    public function archive(PageRepository $repository): Response
    {
        return $this->render('admin/page/index.html.twig', [
            'pages' => $repository->getTrashed(),
            'pageName' => 'Pages archive',
            'isArchive' => true
        ]);
    }
}
