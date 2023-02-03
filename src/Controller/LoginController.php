<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="api_login")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    public function index(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $data = $request->toArray();
        $userName = array_key_exists('username', $data) ? $data['username'] : null;
        $password = array_key_exists('password', $data) ? $data['password'] : null;
        $user = $userRepository->findOneBy(['username' => $userName]);
        if (!$user) throw new NotFoundHttpException('User not found!');

        if (!$hasher->isPasswordValid($user, $password)) {
            throw new AccessDeniedHttpException('Wrong password');
        }

        if (!$user->getApiToken()) {
            $token = $hasher->hashPassword($user, uniqid(). time());

            $user->setApiToken($token);
            $userRepository->add($user, true);
        }

        return $this->json([
            'userName' => $user->getUserIdentifier(),
            'token' => $user->getApiToken(),
            'roles' => $user->getRoles()
        ]);
    }
}
