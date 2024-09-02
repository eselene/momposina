<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, FormFactoryInterface $formFactory): Response
    {
        // Retrieve the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        // Create the registration form
        $user = new User();
        $form = $formFactory->create(RegistrationFormType::class, $user);
    
        return $this->render('security/login_register.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // This method will never be executed, Symfony handles the logout automatically.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        // Create a new User instance
        $user = new User();
        // Create the registration form
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        dump($request->request->all());
        // Check if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
       
            // Save the user
            $entityManager->persist($user);
            $entityManager->flush();

            // Log in the user after registration
            $security->login($user, 'form_login', 'main');
            $this->addFlash('success', 'Votre compte a été créé avec succès. Vous êtes maintenant connecté.');

            // Redirect to the login page
            return $this->redirectToRoute('app_login');
        }

        // Render the template with both the login and registration form
        return $this->render('security/login_register.html.twig', [
            'registrationForm' => $form->createView(),
            'last_username' => null,
            'error' => null,
        ]);
    }
    
}
