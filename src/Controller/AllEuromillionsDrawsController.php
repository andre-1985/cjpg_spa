<?php

namespace App\Controller;

use App\Repository\DrawEuromillionsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllEuromillionsDrawsController extends AbstractController
{
    private PaginatorInterface $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    #[Route('/Euromillions/Tirages', name: 'app_all_euromillions_draws')]
    public function index(DrawEuromillionsRepository $repository): Response
    {
        $allDrawsEuromillions = $repository->findAll();

        return $this->render('pages/all_euromillions_draws/index.html.twig', [
            'allDrawsEuromillions' => $allDrawsEuromillions,
        ]);
    }

    #[Route('/Euromillions/Tirage{drawId}', name: 'app_draw_euromillions')]
    public function showDraw($drawId, DrawEuromillionsRepository $repository)
    {
        $detailedDraw = $repository->find($drawId);

        return $this->render('pages/all_euromillions_draws/draw_euromillions.html.twig', [
            'detailedDraw' => $detailedDraw,
        ]);
    }
}
