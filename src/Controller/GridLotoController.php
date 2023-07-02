<?php

namespace App\Controller;

use App\Entity\UserSelectionLoto;
use App\Form\GridLotoType;
use App\Repository\DrawLotoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GridLotoController extends AbstractController
{
    private PaginatorInterface $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    #[Route('/Loto/Grille', name: 'app_grid_loto')]
    public function index(Request $request, DrawLotoRepository $repository): Response
    {
        $selectionLoto = new UserSelectionLoto();

        $form = $this->createForm(GridLotoType::class, $selectionLoto)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userSelection = $form->getData();
            $userSelectionRequest = $repository->findDrawsByUserSelection($userSelection->ballsSelectionLoto, $userSelection->luckyNumberSelection);
            $userSelectionResults = [];

            foreach ($userSelectionRequest as $userSelectionRq) {
                $userResultsDrawId = 'draw_' . $userSelectionRq->getId();
                $drawId = $userResultsDrawId;

                $ballsRq = $userSelectionRq->getBallsArray();
                $luckyNumberRq = $userSelectionRq->getLuckyNumberArray();

                $userCommonBallsArray = array_intersect($userSelection->ballsSelectionLoto, $ballsRq);
                $userCommonLuckyNumberArray = array_intersect($userSelection->luckyNumberSelection, $luckyNumberRq);

                if (!empty($userCommonBallsArray) && !empty($userCommonLuckyNumberArray)) {
                    $countCommonBallsArray = count($userCommonBallsArray);
                    $countCommonLuckyNumberArray = count($userCommonLuckyNumberArray);
                    $categoryName = 'balls_' . $countCommonBallsArray . '_' . $countCommonLuckyNumberArray;

                } elseif (empty($userCommonBallsArray) && !empty($userCommonLuckyNumberArray)) {
                    $categoryName = '_luckyNumber_';

                } else {
                    $countCommonBallsArray = count($userCommonBallsArray);
                    $categoryName = 'balls_' . $countCommonBallsArray;
                }
                $userSelectionResults[$categoryName][$drawId] = [
                    'userCommonBallsArray' => $userCommonBallsArray,
                    'userCommonLuckyNumberArray' => $userCommonLuckyNumberArray,
                    'drawId' => $userSelectionRq->getId(),
                ];
            }

            krsort($userSelectionResults);

            $session = $request->getSession();
            $session->set('userSelection', $userSelection);
            $session->set('userSelectionResults', $userSelectionResults);

            return $this->redirectToRoute('app_user_group_results_loto');
        }

        return $this->render('pages/grid_loto/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/Loto/Resultats', name: 'app_user_group_results_loto')]
    public function results(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->has('userSelection') || !$session->has('userSelectionResults')) {
            return $this->redirectToRoute('app_grid_loto');
        }

        $userSelection = $session->get('userSelection');
        $userSelectionResults = $session->get('userSelectionResults');

        return $this->render('pages/grid_loto/user_loto_results.html.twig', [
            'userSelection' => $userSelection,
            'userSelectionResults' => $userSelectionResults,
        ]);
    }

    #[Route('/Loto/Resultats/{groupId}', name: 'app_group_details_loto')]
    public function groupDetails($groupId, Request $request, DrawLotoRepository $repository): Response
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

        return $this->render('pages/grid_loto/user_group_results.html.twig', [
            'groupResults' => $groupResults,
            'groupDetails' => $groupDetailsPaginate,
        ]);
    }
}
