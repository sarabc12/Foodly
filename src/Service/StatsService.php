<?php

namespace App\Service;

use App\Repository\OrderRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class StatsService {
    public function __construct(
        private OrderRepository $orderRepository,
        private ChartBuilderInterface $chartBuilder
    )
    {}

    public function getOrderRateChart(): Chart{
        $data = $this->orderRepository->countOrdersByMonthForCurrentYear();
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $values = array_fill(0, 12, 0);

        foreach($data as $row) {
            $monthIndex = (int)$row['month']-1;
            $values[$monthIndex] = (int)$row['count'];
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Order activity',
                    'borderColor' => '#ff916e',
                    'data' => $values,
                    'borderRadius' => 6,
                    'barThickness' => 20,
                ],
            ],
        ]);

        $chart->setOptions([
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'drawBorder' => false,
                        'color' => '#f0f0f0', 
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false, 
                    ],
                ],
            ],
        ]);

        return $chart;
    }

}