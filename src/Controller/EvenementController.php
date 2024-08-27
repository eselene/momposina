<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    private CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findAllOrderByDate();
        $deleteForms = [];
        
        foreach ($evenements as $evenement) {
            $deleteForms[$evenement->getId()] = $this->createDeleteForm($evenement->getId())->createView();
        }

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'delete_forms' => $deleteForms,
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, ValidatorInterface $validator): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement, [
            'is_edit' => false, // On indique qu'il s'agit d'une création
        ]);        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo1')->getData();

            if ($imageFile) {
                $errors = $validator->validate(
                    $imageFile,
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG ou PNG).',
                    ])
                );

                if (count($errors) > 0) {
                    $this->addFlash('error', (string)$errors);
                    return $this->render('evenement/new.html.twig', [
                        'evenement' => $evenement,
                        'form' => $form,
                    ]);
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est apparue pendant le téléchargement du fichier.');
                }
                // Enregistre seulement le nom du fichier dans la base de données
                $evenement->setPhoto1($newFilename);
            }

            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'Événement créé avec succès !');
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager, SluggerInterface $slugger, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement, [
            'is_edit' => $evenement->getId() !== null, // Si l'ID est non nul, c'est une édition
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('photo1')->getData();

            if ($imageFile) {
                $errors = $validator->validate(
                    $imageFile,
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG ou PNG).',
                    ])
                );

                if (count($errors) > 0) {
                    $this->addFlash('error', (string)$errors);
                    return $this->render('evenement/edit.html.twig', [
                        'evenement' => $evenement,
                        'form' => $form,
                    ]);
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory') , 
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est apparue pendant le téléchargement du fichier.');
                }

                $evenement->setPhoto1($newFilename);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Événement modifié avec succès !');

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $logger->info('Evenement deletion requested for ID: {id}', ['id' => $evenement->getId()]);

        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {

            $entityManager->remove($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'Événement supprimé avec succès !');
            $logger->info('Evenement with ID {id} deleted successfully.', ['id' => $evenement->getId()]);
            $this->addFlash('success', 'Evenement supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
            $logger->error('Invalid CSRF token for evenement deletion. Evenement ID: {id}', ['id' => $evenement->getId()]);
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_evenement_delete', ['id' => $id]))
            ->setMethod('POST')
            ->add('_token', HiddenType::class, [
                'data' => $this->csrfTokenManager->getToken('delete' . $id)->getValue(),
            ])
            ->getForm();
    }
    
}
