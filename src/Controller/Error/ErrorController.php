<?php

namespace App\Controller\Error;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function show(FlattenException $exception){
       switch ($exception->getStatusCode()){
           case 404:
               return $this->render('frontend/errors/404.html.twig');
           case 403:
               return $this->render('frontend/errors/403.html.twig');
           default:
               return $this->render('frontend/errors/500.html.twig');
       }
    }
}