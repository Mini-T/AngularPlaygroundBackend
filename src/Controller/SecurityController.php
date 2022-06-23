<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use \Symfony\Component\HttpFoundation\Request;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;
use function PHPUnit\Framework\objectEquals;

#[Route(path: '/api')]
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SerializerInterface $serializer): JsonResponse
    {
         if ($this->getUser()) {
             return new JsonResponse($this->getUser(), Response::HTTP_OK);
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $array = [$lastUsername, $this->getUser()];
        $data = $serializer->serialize($array, 'json');
       return new JsonResponse($data, Response::HTTP_OK, json: true);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/register', name: 'app_register')]
    public function register(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordEncoder){

        $data = $request -> getContent();
        $ObjectData = json_decode($data, true);
        $user = new User();
        $email = $ObjectData['email'];
        $username = $ObjectData['username'];
        $password = $passwordEncoder->hashPassword($user, $ObjectData['password']);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setUsername($username);

        // 4) save the User!
        $entityManager->getRepository(User::class);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse($user);
    }

}
