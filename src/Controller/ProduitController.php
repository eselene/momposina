<?php

namespace App\Controller;

use App\Form\ProduitSearchType;
use App\Entity\Produit;
use App\Form\ProduitType;

use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    private CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('admin/produit', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
    try {        
        // Crée une requête pour récupérer les produits
        $query = $produitRepository->findAllOrderByName();    
        // Paginer la requête
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), //page actuelle
            5 
        );
    
        return $this->render('produit/index.html.twig', [
            'pagination' => $pagination,
        ]);
    } catch (\Exception $e) {
        $this->container->get('logger')->error('Erreur lors de la récupération des produits : ' . $e->getMessage());
        $this->addFlash('error', 'Une erreur est survenue lors de la récupération des produits. Veuillez réessayer.');

        return $this->redirectToRoute('app_evenements');
    }        
    }

    #[Route('admin/produit/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit, [
            'is_edit' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        try {
            $imageFile = $form->get('photo1')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                // Enregistre seulement le nom du fichier dans la base de données
                $produit->setPhoto1($newFilename);
            }

            $entityManager->persist($produit);
            $entityManager->flush();
            // Ajout des messages flash
            $this->addFlash('success', 'Produit créé avec succès!');
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        } catch (FileException $e) {
            // Log l'erreur pour les développeurs
            $this->container->get('logger')->error('Erreur lors du téléchargement du fichier : ' . $e->getMessage());

            // Affiche un message d'erreur à l'utilisateur
            $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image. Veuillez réessayer.');
        } catch (\Exception $e) {
            // Log l'erreur générale
            $this->container->get('logger')->error('Erreur lors de la création du produit : ' . $e->getMessage());

            // Affiche un message d'erreur générique à l'utilisateur
            $this->addFlash('error', 'Une erreur est survenue lors de la création du produit. Veuillez réessayer.');
        }
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('admin/produit/{id}/show', name: 'app_produit_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('admin/produit/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProduitType::class, $produit, [
            'is_edit' => $produit->getId() !== null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
            $imageFile = $form->get('photo1')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    
                    $produit->setPhoto1($newFilename);
                }
                
                $entityManager->flush();

                $this->addFlash('success', 'Produit modifié avec succès!');
                return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
            } catch (FileException $e) {
            $this->container->get('logger')->error('Erreur lors du téléchargement du fichier : ' . $e->getMessage());
            $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image. Veuillez réessayer.');
        } catch (\Exception $e) {
            $this->container->get('logger')->error('Erreur lors de la modification du produit : ' . $e->getMessage());
            $this->addFlash('error', 'Une erreur est survenue lors de la modification du produit. Veuillez réessayer.');
            }
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('admin/produit/{id}/delete', name: 'app_produit_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
        try {
            $entityManager->remove($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Produit supprimé avec succès!');
        } catch (\Exception $e) {
            $this->container->get('logger')->error('Erreur lors de la suppression du produit : ' . $e->getMessage());
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression du produit. Veuillez réessayer.');
        }
        } else {
        $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

}
