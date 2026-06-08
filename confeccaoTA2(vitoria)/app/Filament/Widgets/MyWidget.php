<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Produto;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MyWidget extends BaseWidget
{
    protected ?string $heading = 'Dashboard de Vendas'; // ✅ sem static
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $totalVendas = Pedido::sum('valor_total') ?? 0;
        $totalPedidos = Pedido::count();
        $totalClientes = Cliente::count();
        $totalProdutos = Produto::count();
        
        $vendasMes = Pedido::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('valor_total') ?? 0;
        
        $vendasMesAnterior = Pedido::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('valor_total') ?? 0;
        
        $crescimento = $vendasMesAnterior > 0 
            ? (($vendasMes - $vendasMesAnterior) / $vendasMesAnterior) * 100 
            : 0;
        
        $pedidosPendentes = Pedido::where('status', 'pendente')->count();
        $pedidosAndamento = Pedido::where('status', 'em_andamento')->count();
        $pedidosConcluidos = Pedido::where('status', 'concluido')->count();
        
        return [
            Stat::make('Total de Vendas', 'R$ ' . number_format($totalVendas, 2, ',', '.'))
                ->description('Total geral')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 8, 9]),
            
            Stat::make('Vendas este Mês', 'R$ ' . number_format($vendasMes, 2, ',', '.'))
                ->description($crescimento >= 0 ? '+' . number_format($crescimento, 1) . '% em relação ao mês passado' : number_format($crescimento, 1) . '% em relação ao mês passado')
                ->descriptionIcon($crescimento >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($crescimento >= 0 ? 'success' : 'danger'),
            
            Stat::make('Total de Pedidos', $totalPedidos)
                ->description($pedidosPendentes . ' pendentes | ' . $pedidosAndamento . ' em andamento | ' . $pedidosConcluidos . ' concluídos')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),
            
            Stat::make('Clientes Ativos', $totalClientes)
                ->description('Clientes cadastrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make('Produtos em Estoque', $totalProdutos)
                ->description('Produtos cadastrados')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),
        ];
    }
}