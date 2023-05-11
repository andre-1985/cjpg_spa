<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllEuromillionsDrawsController extends AbstractController
{
    #[Route('/Euromillions/Tirages', name: 'app_all_euromillions_draws')]
    public function index(): Response
    {
        return $this->render('pages/all_euromillions_draws/index.html.twig', [
            'controller_name' => 'AllEuromillionsDrawsController',
        ]);
    }
}
