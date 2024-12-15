<?php
// src/Controller/ProduitController.php

namespace App\Controller;

use App\Form\ProduitSearchType;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\ImageUploader;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

// Route de base pour toutes les actions du contrôleur
#[Route('/admin/produit')]
class ProduitController extends AbstractController
{
    private CsrfTokenManagerInterface $csrfTokenManager;
    private LoggerInterface $logger;
    private const NB_ELEMENTS_PAGE = 10; 

    // Constructeur pour injecter les dépendances
    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, LoggerInterface $logger)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->logger = $logger;
    }

    // Affichage de la liste des produits avec pagination
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->logger->info('Accès à la page index des produits.');
        
        try {
            // Récupération des produits triés par nom
            $query = $produitRepository->findAllOrderByName();

            // Paginer les résultats (10 produits par page)
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                self::NB_ELEMENTS_PAGE
            );

            return $this->render('produit/index.html.twig', [
                'pagination' => $pagination,
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des produits : ' . $e->getMessage());
            $this->addFlash('error', 'Une erreur est survenue.');
            return $this->redirectToRoute('app_produit_index');
        }
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ImageUploader $imageUploader): Response
    {
        $this->logger->info('Accès à la page de création d\'un nouveau produit.');
        
        // Crée une nouvelle instance de Produit
        $produit = new Produit();

        // Crée le formulaire pour ajouter un produit
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Récupère l'image du formulaire
                $imageFile = $form->get('photo1')->getData();

                if ($imageFile) {
                    $this->logger->info('Upload de l\'image du produit.');
                    // Upload de l'image via le service ImageUploader
                    $newFilename = $imageUploader->upload($imageFile);
                    $produit->setPhoto1($newFilename); // Enregistre le nom de fichier dans l'entité Produit
                }

                // Sauvegarde le produit en base de données
                $entityManager->persist($produit);
                $entityManager->flush();

                $this->logger->info('Produit créé avec succès.');
                $this->addFlash('success', 'Produit créé avec succès!');
                return $this->redirectToRoute('app_produit_index');
            } catch (\Exception $e) {
                // Gère les erreurs lors de la création du produit
                $this->logger->error('Erreur lors de la création du produit : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
            }
        }

        // Retourne la vue pour afficher le formulaire de création
        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    // Modification d'un produit existant
    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, ImageUploader $imageUploader): Response
    {
        $this->logger->info('Accès à la page d\'édition du produit ID: ' . $produit->getId());
        
        // Crée le formulaire pour éditer le produit
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $this->logger->info('$form->isValid');

                    // Enregistrer l'ancien nom de fichier
                    $oldFilename = $produit->getPhoto1();
                    $imageFile = $form->get('photo1')->getData();
    
                    $this->logger->info('$imageFile getData');

                    if ($imageFile) {
                    // Upload de la nouvelle image
                        $newFilename = $imageUploader->upload($imageFile);
                        $produit->setPhoto1($newFilename);

                        // Supprimer l'ancienne photo seulement après avoir téléchargé la nouvelle
                        if ($oldFilename) {
                            $oldFilePath = $imageUploader->getTargetDirectory() . '/' . $oldFilename;
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                                $this->logger->info('Ancienne photo supprimée : ' . $oldFilePath);
                            }
                        }
                    }

                    $entityManager->flush();
                    $this->addFlash('success', 'Produit modifié avec succès!');
                    return $this->redirectToRoute('app_produit_index');
                } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la modification : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue.');
            }
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    // Affichage d'un produit
    #[Route('/{id}/show', name: 'app_produit_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Produit $produit): Response
    {
        $this->logger->info('Affichage du produit ID: ' . $produit->getId());

        // Retourne la vue pour afficher les détails d'un produit
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    // Suppression d'un produit
    #[Route('/{id}/delete', name: 'app_produit_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $this->logger->info('Tentative de suppression du produit ID: ' . $produit->getId());

        // Vérifie la validité du token CSRF
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            try {
                // Supprime le produit de la base de données
                $entityManager->remove($produit);
                $entityManager->flush();

                $this->logger->info('Produit ID: ' . $produit->getId() . ' supprimé avec succès.');
                $this->addFlash('success', 'Produit supprimé avec succès!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression.');
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        // Redirige vers la liste des produits
        return $this->redirectToRoute('app_produit_index');
    }

    // Recherche de produits
    #[Route('/search', name: 'app_produit_search', methods: ['GET', 'POST'])]
    public function search(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Crée le formulaire de recherche
        $form = $this->createForm(ProduitSearchType::class);
        $form->handleRequest($request);

        // Récupère les critères de recherche
        $query = $form->isSubmitted() && $form->isValid()
            ? $form->get('query')->getData()
            : null;

        // Recherche les produits selon les critères ou une catégorie
        $produits = $query
            ? $produitRepository->findByNomNomEsFR($query, $request->query->getInt('sousCategorieId', 0))
            : $produitRepository->findBySousCategorieId($request->query->getInt('sousCategorieId', 0));

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            self::NB_ELEMENTS_PAGE
        );

        $this->logger->info('Affichage des résultats de la recherche.');

        // Retourne la vue avec les résultats de recherche
        return $this->render('produit/search.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/produits/ajax', name: 'ajax_produits', methods: ['GET'])]
    public function ajaxProduits(
        ProduitRepository $produitRepository,
        Request $request
    ): JsonResponse {
        $query = $request->query->get('query');
        $id = $request->query->getInt('id');
    
        if ($query) {
            $produits = $produitRepository->findByNomNomEsFR($query, $id);
        } else {
            $produits = $produitRepository->findBySousCategorieId(['sousCategorie' => $id]);
        }
    
        // Créez un tableau de résultats au format JSON
        $data = [];
        foreach ($produits as $produit) {
            $data[] = [
                'nom' => $produit->getNom(),
                'description' => $produit->getDescription(),
                'photo' => $produit->getPhoto1() ? $this->generateUrl('product_image', ['filename' => $produit->getPhoto1()]) : $this->generateUrl('product_image', ['filename' => 'default.jpeg']),
            ];
        }
    
        return new JsonResponse($data);
    }
    
}
