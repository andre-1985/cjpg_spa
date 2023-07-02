<?php

namespace App\Twig\Components;

use App\Entity\UserSelectionLoto;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('stats_group_results_user_selection_loto')]
final class StatsGroupResultsUserSelectionLotoComponent
{
    public UserSelectionLoto $userSelection;
    public array $groupResults;

    public function __construct(
        private readonly ChartBuilderInterface $chartBuilder,
    )
    {
    }

    public function getBallsChart(): Chart
    {
        $countPerBalls = array_fill_keys($this->userSelection->ballsSelectionLoto, 0);

        foreach ($this->groupResults as $result) {
            if (isset($result['userCommonBallsArray'])) {
                foreach ($countPerBalls as $key => $cpb) {
                    if (in_array($key, $result['userCommonBallsArray'])) {
                        $countPerBalls[$key] += 1;
                    }
                }
            }
        }

        $ballsChart = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        if (isset($countPerBalls)) {
            foreach ($countPerBalls as $countPerBall) {
                $ballsStats[] = $countPerBall;
            }
            $labelsSelection = $this->userSelection->ballsSelectionLoto;
            $dataStats = $ballsStats;
            $dataColors = array_fill(0, count($dataStats), 'rgb(0, 51, 153)');
        }

        $ballsChart->setData([
            'labels' => $labelsSelection,
            'datasets' => [
                [
                    'label' => 'Nombre de fois gagnant',
                    'backgroundColor' => $dataColors,
                    'borderColor' => 'rgb(0, 0, 0)',
                    'data' => $dataStats,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ]);

        $ballsChart->setOptions([
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Nb de fois gagnant'
                    ],
                    'min' => 0,
                    'suggestedMax' => 5,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Boules'
                    ],
                ]
            ]
        ]);

        return $ballsChart;
    }
}
