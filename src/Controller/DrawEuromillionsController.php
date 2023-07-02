<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DrawEuromillionsController extends AbstractController
{
    #[Route('/draw/euromillions', name: 'app_draw_euromillions')]
    public function index(): Response
    {
        return $this->render('draw_euromillions/index.html.twig', [
            'controller_name' => 'DrawEuromillionsController',
        ]);
    }
}
