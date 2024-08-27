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
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $query = $evenementRepository->findAllOrderByDateVisible();
        $pagination = $paginator->paginate(
            $query ,
            $request->query->getInt('page', 1),
            4 // Nombre d'éléments par page
        );

        return $this->render('main/mainEvenement.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    #[Route('/presentation', name: 'app_presentation')]
    public function presentation(): Response
    {
        return $this->render('main/presentation.html.twig');
    }

    #[Route('/alimentation/{id}', name: 'app_alimentation_detail', requirements: ['id' => '\d+'])]
    public function alimentationDetail(int $id, ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findBySousCategorieId(['sousCategorie' => $id]);

        return $this->render('main/alimentation_detail.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/boisson/{id}', name: 'app_boisson_detail', requirements: ['id' => '\d+'])]
    public function boissonDetail(int $id, ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findBySousCategorieId(['sousCategorie' => $id]);

        return $this->render('main/boisson_detail.html.twig', [
            'produits' => $produits,
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

     #[Route('/galerie', name: 'app_galerie')]
    public function galerie(): Response
    {
        $images = [
            [
                'src' => 'images/galerie/barril.jpeg',
                'alt' => 'photo de fûts de chêne et poupée'
            ],
            [
                'src' => 'images/galerie/alcoholFort.jpeg',
                'alt' => 'photo d\'alcohol Fort'
            ],
            [
                'src' => 'images/galerie/cafe.jpeg',
                'alt' => 'photo de fûts de café'
            ],
            [
                'src' => 'images/galerie/poupeeFaitMain.jpeg',
                'alt' => 'photo de poupée fait main'
            ],
            [
                'src' => 'images/galerie/sacAmain.jpeg',
                'alt' => 'photo de sacs à main'
            ],
            [
                'src' => 'images/galerie/doudou.jpeg',
                'alt' => 'photo de doudous'
            ],
            [
                'src' => 'images/galerie/trio.jpeg',
                'alt' => 'photo d\'équipe momposina'
            ],
            [
                'src' => 'images/galerie/produits.jpeg',
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
