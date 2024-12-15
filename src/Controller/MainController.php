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
    private const ELEMENTS_PAR_PAGE_E = 4; //evenement
    private const ELEMENTS_PAR_PAGE_B = 8; //alim et boisson

    #[Route('/', name: 'app_evenements')]
    public function home(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $query = $evenementRepository->findAllOrderByDateVisibleByTen();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            self::ELEMENTS_PAR_PAGE_E // Nombre d'éléments par page
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
        ProduitRepository $produitRepository,
        SousCategorieRepository $sousCategorieRepository,
        PaginatorInterface $paginator,
        Request $request,
        int $id,
        string $typePage
    ): Response {
        // Crée le formulaire de recherche de produits
        $formResearch = $this->createForm(ProduitSearchType::class, null, ['method' => 'GET']);
        $formResearch->handleRequest($request);
                // public function findBySousCategorieId($sousCategorieId): array
        $produits = $produitRepository->findBySousCategorieId(['sousCategorie' => $id]);
        // $produits = $produitRepository->findBy(['sousCategorie' => $id, 'visibleWeb' => true]);
        if ($formResearch->isSubmitted() && $formResearch->isValid()) {
            $query = $formResearch->get('query')->getData();
            // Vérification de la requête
            if ($query) {
                $produits = $produitRepository->findByNomNomEsFR($query, $id);
            }
        }

        // Récupère la sous-catégorie par son ID
        $sousCategorie = $sousCategorieRepository->find($id);
        // Récupère la description de la sous-catégorie, ou une chaîne vide si non trouvée
        $sousCategorieNom = $sousCategorie ? $sousCategorie->getDescription() : '';

        // Paginate les résultats des produits
        $pagination = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            self::ELEMENTS_PAR_PAGE_B
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
                'src' => 'images/galerie/barril.webp',
                'alt' => 'photo de fûts de chêne et poupée',
                'desc' => 'Plongez dans l\'ambiance festive et colorée de La Momposina Tienda Latina ! '
            ],
            [
                'src' => 'images/galerie/alcoholFort.webp',
                'alt' => 'photo d\'alcohol Fort',
                'desc' => 'Découvrez notre large gamme de boissons exotiques et savourez un moment de détente. Chacune de nos bouteilles est une invitation à un voyage gustatif en Amérique Latine ! '
            ],
            [
                'src' => 'images/galerie/cafe.webp',
                'alt' => 'photo de fûts de café',
                'desc' => 'Laissez-vous emporter par l \'arôme envoûtant de nos cafés authentiques !'
            ],
            [
                'src' => 'images/galerie/poupeeFaitMain.webp',
                'alt' => 'photo de poupée fait main',
                'desc' => 'Plongez dans notre univers et découvrez la richesse de notre culture à travers nos produits !'
            ],
            [
                'src' => 'images/galerie/sacAmain.webp',
                'alt' => 'photo de sacs à main',
                'desc' => 'Découvrez notre sélection de produits artisanaux faits main et uniques ! Venez vite, cette collection artisanale exceptionnelle est en exposition pour une durée limitée !'
            ],
            [
                'src' => 'images/galerie/doudou.webp',
                'alt' => 'photo de doudous',
                'desc' => 'Explorez notre boutique et trouvez des trésors cachés à chaque coin. Ne manquez pas cette exposition temporaire et plongez dans notre univers culturel !'
            ],
            [
                'src' => 'images/galerie/trio.webp',
                'alt' => 'photo d\'équipe momposina',
                'desc' => 'Venez à La Momposina et rencontrez notre équipe chaleureuse et accueillante. Découvrez nos délicieux plats et laissez-vous séduire par notre ambiance conviviale !'
            ],
            [
                'src' => 'images/galerie/produits.webp',
                'alt' => 'photo de produits',
                'desc' => 'Explorez notre large sélection de produits et boissons authentiques de l\'Amérique Latine !'
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
