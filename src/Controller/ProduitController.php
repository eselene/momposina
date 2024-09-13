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
            $imageFile = $form->get('photo1')->getData();

            if ($imageFile) {
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
                $produit->setPhoto1($newFilename);
            }

            $entityManager->persist($produit);
            $entityManager->flush();
            // Ajout des messages flash
            $this->addFlash('success', 'Produit créé avec succès!');
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/show', name: 'app_produit_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[Route('admin/produit/{id}', name: 'app_produit_show', methods: ['GET'], requirements: ['id' => '\d+'])]
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
            'is_edit' => $produit->getId() !== null, // Si l'ID est non nul, c'est une édition
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo1')->getData();

            if ($imageFile) {
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

                $produit->setPhoto1($newFilename);
            }
            // $this->getDoctrine()->getManager()->flush();
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }
        // Ajout des messages flash
        // $this->addFlash('success', 'Produit modifié avec succès!');

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('admin/produit/{id}/delete', name: 'app_produit_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit supprimé avec succès!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }
    

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }


    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_produit_delete', ['id' => $id]))
            ->setMethod('POST') // Ensure the method is POST
            ->add('_token', HiddenType::class, [
                'data' => $this->csrfTokenManager->getToken('delete' . $id)->getValue(),
            ])
            ->getForm();
    }
}
