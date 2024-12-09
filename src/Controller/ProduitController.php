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

#[Route('/admin/produit')]
class ProduitController extends AbstractController
{
    private CsrfTokenManagerInterface $csrfTokenManager;
    private LoggerInterface $logger;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, LoggerInterface $logger)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->logger = $logger;
    }

    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->logger->info('Accès à la page index des produits.');
        try {
            // Crée une requête pour récupérer les produits
            $query = $produitRepository->findAllOrderByName();

            // Paginer la requête
            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                10
            );

            $this->logger->info('Produits récupérés avec succès.');

            return $this->render('produit/index.html.twig', [
                'pagination' => $pagination,
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la récupération des produits : ' . $e->getMessage());
            $this->addFlash('error', 'Une erreur est survenue lors de la récupération des produits. Veuillez réessayer.');
            return $this->redirectToRoute('app_produit_index');
        }
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ImageUploader $imageUploader): Response
    {
        $this->logger->info('Accès à la page de création d\'un nouveau produit.');
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $imageFile = $form->get('photo1')->getData();

                if ($imageFile) {
                    $this->logger->info('Upload de l\'image du produit.');
                    $newFilename = $imageUploader->upload($imageFile);
                    $produit->setPhoto1($newFilename);
                }

                $entityManager->persist($produit);
                $entityManager->flush();

                $this->logger->info('Produit créé avec succès.');
                $this->addFlash('success', 'Produit créé avec succès!');
                return $this->redirectToRoute('app_produit_index');
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la création du produit : ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
            }
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, ImageUploader $imageUploader): Response
    {
        $this->logger->info('Accès à la page d\'édition du produit ID: ' . $produit->getId());
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        $this->logger->info('Apres $form->handleRequest($request)');

        if ($form->isSubmitted()) {
            $this->logger->info('$form->isSubmitted');

            if ($form->isValid()) {
                $this->logger->info('$form->isValid');
                try {
                    $imageFile = $form->get('photo1')->getData();
                    $this->logger->info('$imageFile getData');

                    if ($imageFile) {
                        $newFilename = $imageUploader->upload($imageFile);
                        $produit->setPhoto1($newFilename);
                    }

                    $entityManager->flush();
                    $this->addFlash('success', 'Produit modifié avec succès!');
                    return $this->redirectToRoute('app_produit_index');
                } catch (\Exception $e) {
                    $this->logger->error('Erreur lors de la modification du produit : ' . $e->getMessage());
                    $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
                }
            }
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/show', name: 'app_produit_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Produit $produit): Response
    {
        $this->logger->info('Affichage du produit ID: ' . $produit->getId());
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_produit_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $this->logger->info('Tentative de suppression du produit ID: ' . $produit->getId());
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($produit);
                $entityManager->flush();

                $this->logger->info('Produit ID: ' . $produit->getId() . ' supprimé avec succès.');
                $this->addFlash('success', 'Produit supprimé avec succès!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la suppression du produit.');
            }
        } else {
            $this->logger->error('Token CSRF invalide pour la suppression du produit ID: ' . $produit->getId());
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_produit_index');
    }

    #[Route('/search', name: 'app_produit_search', methods: ['GET', 'POST'])]
    public function search(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $form = $this->createForm(ProduitSearchType::class);
        $form->handleRequest($request);

        $query = $form->isSubmitted() && $form->isValid()
            ? $form->get('query')->getData()
            : null;

        $produits = $query
            ? $produitRepository->findByNomNomEs($query, $request->query->getInt('sousCategorieId', 0))
            : $produitRepository->findBySousCategorieId($request->query->getInt('sousCategorieId', 0));

        $pagination = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            5
        );

        $this->logger->info('Affichage des résultats de la recherche.');
        return $this->render('produit/search.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
}
