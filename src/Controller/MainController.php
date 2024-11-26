<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use App\Repository\ProduitRepository;
use App\Repository\SousCategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use App\Entity\Evenement;
// use App\Entity\Produit;
// use App\Entity\SousCategorie;
use App\Form\ProduitSearchType;

class MainController extends AbstractController
{
    private const ALIM = 'Alimentation';
    private const BOISSON = 'Boisson';  
    private const ELEMENTS_PAR_PAGE = 2; 

    #[Route('/', name: 'app_evenements')]
    public function home(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $query = $evenementRepository->findAllOrderByDateVisibleByTen();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            self::ELEMENTS_PAR_PAGE // Nombre d'éléments par page
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

    // * Affiche les détails des produits d'une sous-catégorie spécifique.
    // * Gère la recherche de produits,
    // * et la sélection du template approprié en fonction du type de page.
    private function produitDetail(
        ProduitRepository $produitRepository, SousCategorieRepository $sousCategorieRepository,
        PaginatorInterface $paginator, Request $request,
        int $id, string $typePage
    ): Response {
    // Crée le formulaire de recherche de produits
        $formResearch = $this->createForm(ProduitSearchType::class);
        $formResearch->handleRequest($request);
 
    // Vérifie si le formulaire de recherche a été soumis et est valide
    if ($formResearch->isSubmitted() && $formResearch->isValid()) {
        $query = $formResearch->get('query')->getData();
        // Vérification de la requête
        if ($query) {
            $produits = $produitRepository->findByNomNomEs($query, $id);
        } else {
            $produits = $produitRepository->findBy(['sousCategorie' => $id, 'visibleWeb' => true]);
        }
        } else {
        // Vérifie si le formulaire n'est pas soumis ou s'il n'est pas valide
        $produits = $produitRepository->findBy(['sousCategorie' => $id, 'visibleWeb' => true]);
        }

    // Récupère la sous-catégorie par son ID
        $sousCategorie = $sousCategorieRepository->find($id);
    // Récupère la description de la sous-catégorie, ou une chaîne vide si non trouvée
        $sousCategorieNom = $sousCategorie ? $sousCategorie->getDescription() : '';

    // Paginate les résultats des produits
        $pagination = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            self::ELEMENTS_PAR_PAGE
        );

        // Vérifie si la page est pour une boisson, en ignorant la casse 
        $isPageForBoisson = strtolower($typePage) === 'boisson';
    // Sélectionne le template approprié en fonction du type de page
        $template = $isPageForBoisson ? 'main/boisson_detail.html.twig' : 'main/alimentation_detail.html.twig';

    // Rend la vue avec les données nécessaires
        return $this->render($template, [
            'pagination' => $pagination,
            'formResearch' => $formResearch->createView(),
            'sousCategorieNom' => $sousCategorieNom,
            'pageTitle' => $typePage,
            'isBoisson' => $isPageForBoisson,
        ]);
    }

    #[Route('/alimentation/{id}', name: 'app_alimentation_detail', requirements: ['id' => '\d+'])]
    public function alimentationDetail(
        ProduitRepository $produitRepository,
        SousCategorieRepository $sousCategorieRepository,
        PaginatorInterface $paginator,
        Request $request,
        int $id
    ): Response {
        return $this->produitDetail($produitRepository, $sousCategorieRepository, $paginator, $request, $id, self::ALIM);
    }

    #[Route('/boisson/{id}', name: 'app_boisson_detail', requirements: ['id' => '\d+'])]
    public function boissonDetail(
        ProduitRepository $produitRepository,
        SousCategorieRepository $sousCategorieRepository,
        PaginatorInterface $paginator,
        Request $request,
        int $id
    ): Response {
        return $this->produitDetail($produitRepository, $sousCategorieRepository, $paginator, $request, $id, self::BOISSON);
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
