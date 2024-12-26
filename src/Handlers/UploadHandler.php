<?php

namespace App\Handlers;

use CodeBuds\WebPConverter\WebPConverter;
use Gedmo\Sluggable\Util\Urlizer;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHandler
{
    private string $mediaUploadsPath;

    public function __construct(string $mediaUploadsPath)
    {
        $this->mediaUploadsPath = $mediaUploadsPath;
    }

    public function uploadImage(UploadedFile $uploadedFile)
    {
        $filesystem = new Filesystem();
        $date = new \DateTime();
        $folders = $date->format('Y').'/'.$date->format('m').'/';

        $destination = $this->mediaUploadsPath.'/'.$folders;

        if (!$filesystem->exists($destination)) {
            $filesystem->mkdir($destination);
        }

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExtension = $uploadedFile->guessExtension();
        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid('', true).'.'.$fileExtension;
        $isImage = $uploadedFile->isReadable() && in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true);


        if ($isImage) {
            $uploadedFile->move(
                $destination,
                $newFilename
            );

            if (!in_array($fileExtension, ['svg', 'webp'])) {
                ImageHandler::optimize($destination.$newFilename);
            }

            return $folders.$newFilename;
        }

        throw new ExtensionFileException('File is not image');
    }
}