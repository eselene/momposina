<?php
// src/Controller/ContactController.php
// src/Controller/ContactController.php
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request): Response
    // public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('hidden_field')->getData() !== '') {
                return $this->redirectToRoute('contact'); // Rediriger ou ignorer la soumission
            }

            // Send the email
            // $mailer->send(...);

            $this->addFlash('success', 'Votre message a été envoyé avec succès !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
