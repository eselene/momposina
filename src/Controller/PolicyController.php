<?php
// src/Controller/PolicyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolicyController extends AbstractController
{
    #[Route('/cookies-policy', name: 'cookies_policy')]
    public function cookiesPolicy(): Response
    {
        return $this->render('policy/cookies.html.twig');
    }
}
