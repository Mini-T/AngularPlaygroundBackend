<?php

namespace App\Controller;

use App\Entity\User;
use http\Env\Request;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: '/api')]
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SerializerInterface $serializer): Response
    {
         if ($this->getUser()) {
             return new JsonResponse($this->getUser(), Response::HTTP_OK);
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $data = $serializer->serialize($lastUsername, 'json');
       return new JsonResponse($data, Response::HTTP_OK, json: true);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/register', name: 'app_register')]
    public function register(UserPasswordHasherInterface $passwordEncoder){
        $user = new User();
        $password = $passwordEncoder->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        // 4) save the User!
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

}
