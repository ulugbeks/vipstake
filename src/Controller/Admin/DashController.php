<?php

namespace App\Controller\Admin;

use App\Entity\Admin\User;
use App\Repository\Frontend\CountryRepository;
use App\Service\Admin\SpinParserService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class DashController extends AdminController
{
    #[Route('/admin/', name: 'admin_index')]
    public function index(): Response
    {
        return $this->render('admin/base.html.twig', [
            'pageName' => 'Dash',
        ]);
    }

    #[Route('/admin/clear-cache', name: 'admin_clear_cache')]
    public function clearCache(KernelInterface $kernel){
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cache:clear',
            '--env'   => 'prod',
        ]);

        $output = new NullOutput();
        $application->run($input, $output);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}