<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class MyWidget extends ChartWidget
{
    protected ?string $heading = 'Meu Widget';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        return [
            'labels' => ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio'],
            'datasets' => [
                [
                    'label' => 'Vendas',
                    'data' => [10, 20, 15, 30, 25],
                    'backgroundColor' => 'rgba(188, 75, 192, 0.2)',
                    'borderColor' => 'rgb(192, 75, 186)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
