<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Vendas por Mês';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Últimos 6 meses
        $vendas = Pedido::select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as ano'),
                DB::raw('SUM(valor_total) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        $labels = [];
        $valores = [];

        foreach ($vendas as $venda) {
            $nomeMes = $this->getNomeMes($venda->mes);
            $labels[] = $nomeMes . '/' . $venda->ano;
            $valores[] = $venda->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Vendas (R$)',
                    'data' => $valores,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getNomeMes($mes)
    {
        $meses = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];
        return $meses[$mes];
    }
}