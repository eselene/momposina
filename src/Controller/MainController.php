<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Http\HttpResponse;

use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Evenement;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Repository\EvenementRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Query\AST\OrderByItem;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_evenements')]
    public function home(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $evenements = $evenementRepository->findAll();

        $pagination = $paginator->paginate(
            $evenements,
            $request->query->getInt('page', 1),
            6 // Nombre d'éléments par page
        );

        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $evenements = $evenementRepository->findAllOrderByDate();

        return $this->render('main/mainEvenement.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/presentation', name: 'app_presentation')]
    public function presentation(): Response
    {
        return $this->render('main/presentation.html.twig');
    }
    #[Route('/alimentation', name: 'app_alimentation')]
    public function alimentation(EntityManagerInterface $entityManager): Response
    {
        $sousCategories = $entityManager->getRepository(SousCategorie::class)
            ->findBy(['categorie' => 1], ['id' => 'ASC']); // Trier par ID croissant

        return $this->render('main/alimentation.html.twig', [
            'sousCategories' => $sousCategories,
        ]);
    }

    #[Route('/boisson', name: 'app_boisson')]
    public function boisson(EntityManagerInterface $entityManager): Response
    {
        $sousCategories = $entityManager->getRepository(SousCategorie::class)
            ->findBy(['categorie' => 2], ['id' => 'ASC']); // Trier par ID croissant

        return $this->render('main/boisson.html.twig', [
            'sousCategories' => $sousCategories,
        ]);
    }

    #[Route('/plats', name: 'app_plats')]
    public function plats(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findByCategorie(3);
        return $this->render('main/plats.html.twig', [
            'plats' => $produits,
        ]);
    }
    // #[Route('/alimentation/{id}', name: 'app_alimentation')]
    // public function alimentation(int $id, Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $query = $request->query->get('query');
    //     $alimentations = [];

    //     if ($query) {
    //         $alimentations = $entityManager->getRepository(Produit::class)->findById(['sousCategorie' => $id]);
    //     }

    //     return $this->render('main/alimentation.html.twig', [
    //         'alimentations' => $alimentations,
    //         'query' => $query,
    //     ]);
    // }

    // #[Route('/boisson/{id}', name: 'app_boisson')]
    // public function boisson(int $id, Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $query = $request->query->get('query');
    //     $boissons = [];

    //     if ($query) {
    //         $boissons = $entityManager->getRepository(Produit::class)->findById(['sousCategorie' => $id]);
    //     }

    //     return $this->render('main/boisson.html.twig', [
    //         'boissons' => $boissons,
    //         'query' => $query,
    //     ]);
    // }

    // #[Route('/sous-categories', name: 'app_sous_categories')]
    // public function sousCategories(EntityManagerInterface $entityManager): Response
    // {
    //     $sousCategoriesAlimentation = $entityManager->getRepository(SousCategorie::class)->findBy(['categorie' => 1]);
    //     $sousCategoriesBoisson = $entityManager->getRepository(SousCategorie::class)->findBy(['categorie' => 2]);

    //     return $this->render('main/sous_categories.html.twig', [
    //         'sousCategoriesAlimentation' => $sousCategoriesAlimentation,
    //         'sousCategoriesBoisson' => $sousCategoriesBoisson,
    //     ]);
    // }

    #[Route('/galerie', name: 'app_galerie')]
    public function galerie(): Response
    {
        $images = [
            [
                'src' => 'images/galerie/barril.jpg',
                'alt' => 'photo de fûts de chêne et poupée'
            ],
            [
                'src' => 'images/galerie/alcoholFort.jpg',
                'alt' => 'photo d\'alcohol Fort'
            ],
            [
                'src' => 'images/galerie/cafe.jpg',
                'alt' => 'photo de fûts de café'
            ],
            [
                'src' => 'images/galerie/poupeeFaitMain.jpg',
                'alt' => 'photo de poupée fait main'
            ],
            [
                'src' => 'images/galerie/sacAmain.jpg',
                'alt' => 'photo de sacs à main'
            ],
            [
                'src' => 'images/galerie/doudou.jpg',
                'alt' => 'photo de doudous'
            ],
            [
                'src' => 'images/galerie/trio.jpg',
                'alt' => 'photo d\'équipe momposina'
            ],
            [
                'src' => 'images/galerie/produits.jpg',
                'alt' => 'photo de produits'
            ],
        ];

        return $this->render('main/galerie.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig');
    }
}
