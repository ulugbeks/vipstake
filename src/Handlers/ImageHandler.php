<?php

namespace App\Handlers;

use CodeBuds\WebPConverter\WebPConverter;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ImageHandler
{
    public static function optimize($imagePath){
        $optimizer = OptimizerChainFactory::create();
        $optimizer->optimize($imagePath);
        if(pathinfo($imagePath, PATHINFO_EXTENSION) !== 'svg'){
            WebPConverter::createWebPImage($imagePath, [
                'saveFile' => true
            ]);
        }
    }
}