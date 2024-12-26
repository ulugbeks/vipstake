<?php

namespace App\Controller\Admin;

use App\Attribute\Admin\Editable;
use App\Attribute\Admin\Nav;
use App\Entity\Admin\User;
use App\Repository\Admin\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AdminController
{
    #[Route('/admin/user', name: 'admin_user')]
//    #[Nav(icon: '/admin/img/user.svg', name: 'Users', order: '2')]
    public function index(UserRepository $repository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repository->findAll(),
            'pageName' => 'Users'
        ]);
    }

    #[Route('/admin/user/{id}', name: 'admin_user_edit')]
    #[Editable(User::class, Editable::ACTION_EDIT)]
    public function item(User $user)
    {
        return $this->json($user);
    }
}
