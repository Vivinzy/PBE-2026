<?php

namespace App\Filament\Widgets;

use App\Models\PedidoItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends ChartWidget
{
    protected static ?string $heading = 'Produtos Mais Vendidos';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $topProdutos = PedidoItem::select(
                'produto_id',
                DB::raw('SUM(quantidade) as total_vendido')
            )
            ->with('produto')
            ->groupBy('produto_id')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        $labels = [];
        $valores = [];

        foreach ($topProdutos as $item) {
            $labels[] = $item->produto->nome ?? 'Produto ID: ' . $item->produto_id;
            $valores[] = $item->total_vendido;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Quantidade Vendida',
                    'data' => $valores,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}