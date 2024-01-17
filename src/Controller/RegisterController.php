<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\MailService;
use App\Security\AppFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppFormAuthenticator $authenticator, EntityManagerInterface $em, MailService $mailservice): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordHasher->hashPassword($user,$form->get('password')->getData()));
            $user->setRoles(['ROLE_USER']);
            $em->persist($user);
            $em->flush();

            $html = "<p>Bonjour {$user->getFirstname()} nous vous remercions de votre inscription sur Good Eats Corner!</p>";

            $mailservice->send("goodeatscorner@gmail.com", $user->getEmail(), "Inscription",  $html, "Bienvenue sur Good Eats Corner");

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
