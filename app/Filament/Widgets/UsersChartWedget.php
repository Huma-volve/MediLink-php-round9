<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class UsersChartWedget extends ChartWidget
{
    // protected int|string|array $columnSpan = [
    //     'sm' => 12,
    //     'md' => 6,
    // ];

    protected ?string $heading = 'Users Chart Wedget';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog Users created',
                    'data' => [500 , 320 , 400],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                ],
            ],
            'labels' => ['A' , 'B' , 'C'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
