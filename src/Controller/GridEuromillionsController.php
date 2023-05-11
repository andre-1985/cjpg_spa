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

class GridEuromillionsController extends AbstractController
{
    private PaginatorInterface $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    #[Route('/Euromillions/Grille', name: 'app_grid_euromillions')]
    public function index(Request $request, DrawEuromillionsRepository $repository): Response
    {
        $selectionEuromillions = new UserSelectionEuromillions();

        $form = $this->createForm(GridEuromillionsType::class, $selectionEuromillions)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userSelection = $form->getData();
            $userSelectionRequest = $repository->findDrawsByUserSelection($userSelection->ballsSelectionEuromillions, $userSelection->starsSelection);
            $userSelectionResults = [];

            foreach ($userSelectionRequest as $userSelectionRq) {
                $userResultsDrawId = 'draw_' . $userSelectionRq->getId();
                $drawId = $userResultsDrawId;

                $ballsRq = $userSelectionRq->getBallsArray();
                $starsRq = $userSelectionRq->getStarsArray();

                $userCommonBallsArray = array_intersect($userSelection->ballsSelectionEuromillions, $ballsRq);
                $userCommonStarsArray = array_intersect($userSelection->starsSelection, $starsRq);

                if (!empty($userCommonBallsArray) && !empty($userCommonStarsArray)) {
                    $countCommonBallsArray = count($userCommonBallsArray);
                    $countCommonStarsArray = count($userCommonStarsArray);
                    $categoryName = 'balls_' . $countCommonBallsArray . '_' . $countCommonStarsArray;

                } elseif (empty($userCommonBallsArray) && !empty($userCommonStarsArray)) {
                    $countCommonStarsArray = count($userCommonStarsArray);
                    $categoryName = '_stars_' . $countCommonStarsArray;

                } else {
                    $countCommonBallsArray = count($userCommonBallsArray);
                    $categoryName = 'balls_' . $countCommonBallsArray;
                }
                $userSelectionResults[$categoryName][$drawId] = [
                    'userCommonBallsArray' => $userCommonBallsArray,
                    'userCommonStarsArray' => $userCommonStarsArray,
                    'drawId' => $userSelectionRq->getId(),
                ];
            }

            krsort($userSelectionResults);

            $session = $request->getSession();
            $session->set('userSelection', $userSelection);
            $session->set('userSelectionResults', $userSelectionResults);

            return $this->redirectToRoute('app_user_group_results_euromillions');
        }

        return $this->render('pages/grid_euromillions/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/Euromillions/Resultats', name: 'app_user_group_results_euromillions')]
    public function results(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->has('userSelection') || !$session->has('userSelectionResults')) {
            return $this->redirectToRoute('app_grid_euromillions');
        }

        $userSelection = $session->get('userSelection');
        $userSelectionResults = $session->get('userSelectionResults');

        return $this->render('pages/grid_euromillions/user_euromillions_results.html.twig', [
            'userSelection' => $userSelection,
            'userSelectionResults' => $userSelectionResults,
        ]);
    }

    #[Route('/Euromillions/Resultats/{groupId}', name: 'app_group_details_euromillions')]
    public function groupDetails($groupId, Request $request, DrawEuromillionsRepository $repository): Response
    {
        $session = $request->getSession();
        $userSelectionResults = $session->get('userSelectionResults');

        if (!$userSelectionResults || !isset($userSelectionResults[$groupId])) {
            throw $this->createNotFoundException('Group not found');
        }

        $groupResults = $userSelectionResults[$groupId];

        $groupDetails = [];
        foreach ($groupResults as $key => $result) {
            $detailedDraw = $repository->find($result['drawId']);
            if ($detailedDraw) {
                $groupDetails[$key] = $detailedDraw;
            }
        }

        $groupDetailsPaginate = $this->paginator->paginate(
            $groupDetails,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('pages/grid_euromillions/user_group_results.html.twig', [
            'groupResults' => $groupResults,
            'groupDetails' => $groupDetailsPaginate,
        ]);
    }
}
