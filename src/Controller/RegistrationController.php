<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $entityManager = $doctrine->getManager();
       
        $new_user = new User();
       // $request = Request::createFromGlobals();

        $form = $this->createForm(UserRegistrationType::class);
       
        $form->handleRequest($request);
        
        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $submittedToken = $request->request->all()['user_registration']['_token'];
               if ($this->isCsrfTokenValid('user_registration', $submittedToken)) {
                    $user_registration = $form->getData();
                    $new_user->setName($user_registration['_user']);
                    $new_user->setEmail($user_registration['email']);
                    //$new_user->setRoles(['ROLE_USER']);
                    $plaintextPassword = $user_registration['password'];
                    $hashedPassword = $passwordHasher->hashPassword(
                        $new_user,
                        $plaintextPassword
                    );

                    $new_user->setPassword($hashedPassword);
                    $entityManager->persist($new_user);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_login');

               }       
            }
            else{
                dd($form->getErrors());
            }
        }


        return $this->renderForm('registration/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/sess', name: 'app_sess')]
    public function sess(CsrfTokenManagerInterface $csrftoken): Response
    {
        $csrfToken = $csrftoken->getToken('authenticate')->getValue();
        dd($csrftoken->isTokenValid(new CsrfToken('authenticate', $csrfToken)));
    }
}
