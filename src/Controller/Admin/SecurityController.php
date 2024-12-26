<?php

namespace App\Controller\Admin;

use App\Entity\Admin\User;
use App\Repository\Admin\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/register/', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $email = $data['email'];
        $password = $data['password'];

        if(!trim($password)){
            return $this->json([
                'success' => false,
                'error' => 'Invalid password',
            ], 403);
        }

        if ($userRepository->findOneBy(['email' => $email])) {
            return $this->json([
                'success' => false,
                'error' => 'Email already in use',
            ], 403);
        }


        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($passwordHasher->hashPassword($user, $password));

        $entityManager->persist($user);
        $entityManager->flush();

        $security->login($user);

        return $this->json([
            'success' => true
        ]);
    }

    #[Route(path: '/logout/', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    #[Route(path: '/check-email', name: 'app_check_email', methods: ['GET'])]
    public function checkEmail(Request $request, UserRepository $repository)
    {
       $email = $request->query->get('email');

       return $this->json((bool)$repository->findOneBy(['email' => $email]));
    }

    #[Route('/xhr-login/', name: "app_xhr_login")]
    public function xhrLogin(Request $request, AuthenticationUtils $authenticationUtils): JsonResponse
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            return new JsonResponse(['success' => false, 'message' => $error->getMessage()], 200);
        }

        return new JsonResponse(['success' => true, 'username' => $lastUsername]);
    }
}
