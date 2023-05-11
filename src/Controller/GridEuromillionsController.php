<?php

namespace App\Controller;

use App\Entity\UserSelectionEuromillions;
use App\Form\GridEuromillionsType;
use App\Repository\DrawEuromillionsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

class GridEuromillionsController extends AbstractController
{
    #[Route('/Euromillions/Grille', name: 'app_grid_euromillions')]
    public function index(PaginatorInterface $paginator, Request $request, DrawEuromillionsRepository $repository): Response
    {
        $selectionEuromillions = new UserSelectionEuromillions();

        $form = $this->createForm(GridEuromillionsType::class, $selectionEuromillions)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userSelection = $form->getData();
            $userSelectionRequest = $repository->findDrawsByUserSelection($userSelection->ballsSelectionEuromillions, $userSelection->starsSelection);

            foreach ($userSelectionRequest as $userSelectionRq) {
                $userResultsDrawId = 'draw_' . $userSelectionRq->getId();
                $drawId = $userResultsDrawId;

                $ballsRq = $userSelectionRq->getBallsArray();
                $starsRq = $userSelectionRq->getStarsArray();

                $userCommonBallsArray = array_intersect($userSelection->ballsSelectionEuromillions, $ballsRq);
                $userCommonStarsArray = array_intersect($userSelection->starsSelection, $starsRq);

                if (!empty($userCommonBallsArray) && !empty($userCommonStarsArray)) {
                    $userResultsDrawId = [
                        'userCommonBallsArray' => $userCommonBallsArray,
                        'userCommonStarsArray' => $userCommonStarsArray,
                        'completeDraw' => $userSelectionRq,
                    ];
                    $countCommonBallsArray = count($userResultsDrawId['userCommonBallsArray']);
                    $countCommonStarsArray = count($userResultsDrawId['userCommonStarsArray']);
                    $categoryName = 'balls_' . $countCommonBallsArray . '_' . $countCommonStarsArray;

                } elseif (empty($userCommonBallsArray) && !empty($userCommonStarsArray)) {
                    $userResultsDrawId = [
                        'userCommonStarsArray' => $userCommonStarsArray,
                        'completeDraw' => $userSelectionRq,
                    ];
                    $countCommonStarsArray = count($userResultsDrawId['userCommonStarsArray']);
                    $categoryName = '_stars_' . $countCommonStarsArray;

                } else {
                    $userResultsDrawId = [
                        'userCommonBallsArray' => $userCommonBallsArray,
                        'completeDraw' => $userSelectionRq,
                    ];
                    $countCommonBallsArray = count($userResultsDrawId['userCommonBallsArray']);
                    $categoryName = 'balls_' . $countCommonBallsArray;
                }
                $userSelectionResults[$categoryName][$drawId] = $userResultsDrawId;
            }

            krsort($userSelectionResults);
            $userSelectionResultsPagination = [];

            foreach ($userSelectionResults as $key => $results) {
                if (count($results) > 20) {
                    $resultsPagination = $paginator->paginate(
                        $results,
                        $request->query->getInt('page', 1),
                        20
                    );
                } else {
                    $resultsPagination = $results;
                }
                $userSelectionResultsPagination[$key] = $resultsPagination;
            }

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                return $this->render(
                    'pages/grid_euromillions/user_euromillions_results.html.twig', [
                        'userSelection' => $userSelection,
                        'userSelectionResults' => $userSelectionResults,
                        'userSelectionResultsPagination' => $userSelectionResultsPagination,
                    ]
                );
            }

            return $this->render(
                'pages/grid_euromillions/user_euromillions_results.html.twig', [
                    'userSelection' => $userSelection,
                    'userSelectionResults' => $userSelectionResults,
                    'userSelectionResultsPagination' => $userSelectionResultsPagination,
                ]
            );
        }

        return $this->render('pages/grid_euromillions/index.html.twig', [
            'controller_name' => 'GridEuromillionsController',
        ]);
    }

    #[Route('/Euromillions/GroupResults', name: 'app_user_group_results_euromillions')]
    public function show(): Response
    {
        return $this->render('pages/grid_euromillions/user_group_results.html.twig');
    }
}
