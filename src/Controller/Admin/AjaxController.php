<?php

namespace App\Controller\Admin;

use App\Handlers\UploadHandler;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Routing\Attribute\Route;

class AjaxController extends AdminController
{
    #[Route('/admin/ajax/image-upload', name: 'ajax_image')]
    public function uploadEditorImage(UploadHandler $handler, Request $request)
    {
        $uploadsDir = $this->getParameter('uploads_media_path');

        foreach ($request->files as $file) {
            $file = $handler->uploadImage($file);
            return $this->json([
                'success' => 1,
                'file' => [
                    'url' => $uploadsDir . $file
                ]
            ]);
        }

        return false;
    }


    #[Route('/admin/ajax/gallery-upload', name: 'ajax_gallery')]
    public function uploadGalleryImage(UploadHandler $handler, Request $request)
    {
        $uploadsDir = $this->getParameter('uploads_media_path');

        /** @var UploadedFile $file */
        foreach ($request->files as $file) {
            try {
                $filePath = $handler->uploadImage($file);
            } catch (\Exception $exception) {
                return $this->json(['error' => $exception->getMessage()], 403);
            }

            return new Response($uploadsDir . $filePath);
        }

        return new Response('Not found', 404);
    }

    #[Route('/admin/ajax/load-images', name: 'ajax_get_image')]
    public function getImages(UploadHandler $handler, Request $request)
    {
        $img = $request->get('image');
        $basedir = $this->getParameter('kernel.project_dir');
        $path = $basedir . '/public' . $img;

        if (file_exists($path)) {
            $response = new BinaryFileResponse($path);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

            return $response;
        }

        return $this->json('Not Found', 404, []);
    }
}