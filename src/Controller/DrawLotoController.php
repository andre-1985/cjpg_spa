<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DrawLotoController extends AbstractController
{
    #[Route('/draw/loto', name: 'app_draw_loto')]
    public function index(): Response
    {
        return $this->render('draw_loto/index.html.twig', [
            'controller_name' => 'DrawLotoController',
        ]);
    }
}
