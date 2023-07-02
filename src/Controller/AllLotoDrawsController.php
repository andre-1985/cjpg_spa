<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllLotoDrawsController extends AbstractController
{
    #[Route('/Loto/Tirages', name: 'app_all_loto_draws')]
    public function index(): Response
    {
        return $this->render('pages/all_loto_draws/index.html.twig', [
            'controller_name' => 'AllLotoDrawsController',
        ]);
    }
}
