<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    private LoggerInterface $logger;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, LoggerInterface $logger)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->logger = $logger;
    }

    #[Route('admin/evenement', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        try {
            // Crée une requête pour récupérer les événements
        $query = $evenementRepository->findAllOrderByDate();    

        // Paginer la requête
        $pagination = $paginator->paginate(
            $query, 
                $request->query->getInt('page', 1),
            5 
        );

        return $this->render('evenement/index.html.twig', [
            'pagination' => $pagination,
        ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des événements : ' . $e->getMessage());
            $this->addFlash('error', 'Une erreur est survenue lors de la récupération des événements. Veuillez réessayer.');
            return $this->redirectToRoute('app_evenement_index');
        }
    }

    #[Route('admin/evenement/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, ValidatorInterface $validator): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement, [
            'is_edit' => false,
        ]);        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
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

                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                $evenement->setPhoto1($newFilename);
            }

            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'Événement créé avec succès !');
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
            } catch (FileException $e) {
                $this->logger->error('Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image. Veuillez réessayer.');
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la création de l\'événement : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de la création de l\'événement. Veuillez réessayer.');
            }
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('admin/evenement/{id}', name: 'app_evenement_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('admin/evenement/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager, SluggerInterface $slugger, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement, [
            'is_edit' => $evenement->getId() !== null, // Si l'ID est non nul, c'est une édition
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
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

                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                $evenement->setPhoto1($newFilename);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Événement modifié avec succès !');
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
            } catch (FileException $e) {
                $this->logger->error('Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image. Veuillez réessayer.');
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la modification de l\'événement : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'événement. Veuillez réessayer.');
            }
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('admin/evenement/{id}/delete', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        // $logger->info('Evenement deletion requested for ID: {id}', ['id' => $evenement->getId()]);
        if ($this->isCsrfTokenValid('delete' . $evenement->getId(), $request->request->get('_token'))) {
            try {
            $entityManager->remove($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'Événement supprimé avec succès !');
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la suppression de l\'événement : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression de l\'événement. Veuillez réessayer.');
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
