<?php

namespace App\Controller;

use App\Form\ProduitSearchType;
use App\Entity\Produit;
use App\Form\ProduitType;

use App\Repository\ProduitRepository;
// use App\Repository\Repository;
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

// #[Route('/')]
#[Route('/produit')]
class ProduitController extends AbstractController
{
    private CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        $deleteForms = [];

        foreach ($produits as $produit) {
            $deleteForms[$produit->getId()] = $this->createDeleteForm($produit->getId())->createView();
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'delete_forms' => $deleteForms,
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
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
            // $this->addFlash('success', 'Produit créé avec succès!');
            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/show', name: 'app_produit_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
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

    // #[Route('/{id}/search', name: 'app_produit_search', methods: ['GET', 'POST'])]
    // public function search(Request $request, ProduitRepository $produitRepository ): Response
    // {
    //     $form = $this->createForm(ProduitSearchType::class);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $data = $form->getData();
    //         $query = $data['query'];

    //         // Effectuer la recherche dans la base de données
    //         $products = $produitRepository->findByNomNomEs($id);

    //         return $this->render('product/search_results.html.twig', [
    //             'products' => $products,
    //         ]);
    //     }

    //     return $this->render('product/search.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // TODO*******************
    #[Route('/{id}/delete', name: 'app_produit_delete', methods: ['POST'])]
    // public function delete(int $id, LoggerInterface $logger): Response   
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $logger->info('Product deletion requested for ID: {id}', ['id' => $produit->getId()]);

        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $logger->info('CSRF token valid for product deletion.');

            $entityManager->remove($produit);
            $entityManager->flush();
            // Ajout des messages flash
            // $this->addFlash('success', 'Produit suprimé avec succès!');
            $logger->info('Product with ID {id} deleted successfully.', ['id' => $produit->getId()]);
            $this->addFlash('success', 'Product deleted successfully.');
        } else {
            $logger->error('Invalid CSRF token for product deletion. Product ID: {id}', ['id' => $produit->getId()]);
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
