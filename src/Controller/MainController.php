<?php
namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Evenement;
use App\Entity\SousCategorie;
use App\Repository\EvenementRepository;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_evenements')]
    // public function index(PaginatorInterface $paginator, Request $request): Response
    // {
    //     $repository = $this->getDoctrine()->getRepository(Evenement::class);
    //     $query = $repository->createQueryBuilder('e')
    //         ->getQuery();

    //     $pagination = $paginator->paginate(
    //         $query,
    //         $request->query->getInt('page', 1), // numéro de la page
    //         10 // limite par page
    //     );

    //     return $this->render('main/mainEvenement.html.twig', [
    //         'pagination' => $pagination,
    //             ]);
    //     }

    public function home(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findAll();
        return $this->render('main/mainEvenement.html.twig', [
            'evenements' => $evenements
    ]);

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
        $evenements = [];

        if ($query) {
            $evenements = $entityManager->getRepository(Evenement::class)->findBy(['sousCategorie' => $id]);
        }

        return $this->render('main/alimentation.html.twig', [
            'evenements' => $evenements,
            'query' => $query,
        ]);
    }

    #[Route('/boisson/{id}', name: 'app_boisson')]
    public function boisson(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('query');
        $evenements = [];

        if ($query) {
            $evenements = $entityManager->getRepository(Evenement::class)->findBy(['sousCategorie' => $id]);
        }

        return $this->render('main/boisson.html.twig', [
            'evenements' => $evenements,
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

