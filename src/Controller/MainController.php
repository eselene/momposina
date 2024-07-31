<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;
use App\Entity\SousCategorie;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_evenements')]
    public function home(): Response
    {
        return $this->render('main/mainEvenement.html.twig');
    }

    #[Route('/presentation', name: 'app_presentation')]
    public function presentation(): Response
    {
        return $this->render('main/presentation.html.twig');
    }

    #[Route('/alimentation/{id}', name: 'app_alimentation')]
    public function alimentation(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('query');
        $produits = [];

        if ($query) {
            $produits = $entityManager->getRepository(Produit::class)->findBy(['sousCategorie' => $id]);
        }

        return $this->render('main/alimentation.html.twig', [
            'produits' => $produits,
            'query' => $query,
        ]);
    }

    #[Route('/boisson/{id}', name: 'app_boisson')]
    public function boisson(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('query');
        $produits = [];

        if ($query) {
            $produits = $entityManager->getRepository(Produit::class)->findBy(['sousCategorie' => $id]);
        }

        return $this->render('main/boisson.html.twig', [
            'produits' => $produits,
            'query' => $query,
        ]);
    }

    #[Route('/sous-categories', name: 'app_sous_categories')]
    public function sousCategories(EntityManagerInterface $entityManager): Response
    {
        $sousCategoriesAlimentation = $entityManager->getRepository(SousCategorie::class)->findBy(['categorie' => 1]);
        $sousCategoriesBoisson = $entityManager->getRepository(SousCategorie::class)->findBy(['categorie' => 2]);

        return $this->render('main/sous_categories.html.twig', [
            'sousCategoriesAlimentation' => $sousCategoriesAlimentation,
            'sousCategoriesBoisson' => $sousCategoriesBoisson,
        ]);
    }

    #[Route('/plats', name: 'app_plats')]
    public function plats(): Response
    {
        return $this->render('main/plats.html.twig');
    }

    #[Route('/galerie', name: 'app_galerie')]
    public function galerie(): Response
    {
        return $this->render('main/galerie.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig');
    }
}

