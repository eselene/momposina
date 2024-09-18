<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\AppAuthenticator;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        // dd($request->request->all());
        
        // Vérifiez si le formulaire est soumis et valide        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Your account has been created. Please check your email to verify your account.');
                
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            } catch (\Exception $e) {
                $this->addFlash('error', 'There was a problem creating your account.');
            }
        }

        return $this->render('security/login_register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

