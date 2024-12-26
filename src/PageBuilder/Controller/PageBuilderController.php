<?php

namespace App\PageBuilder\Controller;

use App\Handlers\UploadHandler;
use App\PageBuilder\Service\PageBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/pagebuilder', name: 'pagebuilder_')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class PageBuilderController extends AbstractController
{
    #[Route('/layout', name: 'get_layout')]
    public function layout(PageBuilderService $pageBuilder, Request $request): Response
    {
        $layoutName = $request->query->get('layout');
        $layout = $pageBuilder->renderLayout($layoutName);

        return $this->json($layout);
    }

    #[Route('/block', name: 'get_block')]
    public function block(PageBuilderService $pageBuilder, Request $request): Response
    {
        $layoutName = $request->query->get('name');
        $layout = $pageBuilder->renderBlock($layoutName);

        return $this->json($layout);
    }

    #[Route('/save', name: 'save')]
    public function save(Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);
        $entity = $em->getRepository($data['pageType'])->find($data['pageId']);

        if (!$entity) {
            return $this->createNotFoundException();
        }

        $entity->setContent(json_encode($data['data'], JSON_THROW_ON_ERROR));
        $em->flush();

        return $this->json([
            'status' => 'ok',
        ]);
    }

    #[Route('/upload-image', name: 'upload_image')]
    public function uploadImage(Request $request, UploadHandler $handler)
    {
        $uploadsDir = $this->getParameter('uploads_media_path');
        /** @var UploadedFile $file */
        $file = $request->files->get('file');
        $url = $handler->uploadImage($file);

        return $this->json([
            'url' => $uploadsDir . $url
        ]);
    }
}
