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
use App\Form\ProduitSearchType;

class MainController extends AbstractController
{
    private const ALIM = 'Alimentation';
    private const BOISSON = 'Boisson';
    private const ELEMENTS_PAR_PAGE_E = 4; //evenement
    private const ELEMENTS_PAR_PAGE_B = 8; //alim et boisson

    #[Route('/', name: 'app_evenements')]
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

}
