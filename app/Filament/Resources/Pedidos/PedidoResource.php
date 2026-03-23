<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Pages\CreatePedido;
use App\Filament\Resources\Pedidos\Pages\EditPedido;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\Pages\ViewPedido;
use App\Filament\Resources\Pedidos\Schemas\PedidoForm;
use App\Filament\Resources\Pedidos\Schemas\PedidoInfolist;
use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Pedido';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            Select::make('cliente_id')
                ->relationship('cliente', 'nome')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->label('Cliente'),
            
            Select::make('status')
                ->options([
                    'pendente' => 'Pendente',
                    'em_andamento' => 'Em Andamento',
                    'concluido' => 'Concluído',
                ])
                ->default('pendente')
                ->required(),
                TextInput::make('valor_total')
                    ->label('Valor Total')
                    ->numeric()
                    ->prefix('R$ '),
                Repeater::make('itens')
                    ->relationship('itens')
                    ->schema([
                        Select::make('produto_id')
                            ->relationship('produto', 'nome')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->label('Produto'),

                        TextInput::make('quantidade')
                            ->label('Quantidade')
                            ->numeric()
                            ->required()
                            ->live(onBlur: true)

                            ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::calcularTotal($get,$set))
                            ->columnSpan(2),
                        
                        TextInput::make('preco_unitario')
                            ->label('Preço Unitário')
                            ->numeric()
                            ->prefix('R$')

                            ->required()
                            ->live(onBlur: true)

                            ->afterstateUpdated(fn (Get $get, Set $set) =>
                            self::calcularTotal($get, $set))
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->label('Produtos do Pedido')
                    ->live()

                    ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::calcularTotal($get,$set))


        ]); 
    }

    public static function infolist(Schema $schema): Schema
    {
        return PedidoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cliente.nome')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pendente' => 'warning',
                        'em_andamento' => 'primary',
                        'concluido' => 'success',
                        default => 'secondary',
                    })
                    ->label('Status'),
                TextColumn::make('valor_total')
                    ->label('Valor Total')
                    ->money('BRL', true)
                    ->sortable()
                    ->prefix('R$ '),
                
                TextColumn::make('created_at')
                    ->label('Data do Pedido')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPedidos::route('/'),
            'create' => CreatePedido::route('/create'),
            'view' => ViewPedido::route('/{record}'),
            'edit' => EditPedido::route('/{record}/edit'),
        ];
    }
    public static function calcularTotal(Get $get, Set $set): void
    {
        $itens = $get('itens') ?? [];
        $total = 0;

        foreach ($itens as $item) {
            $quantidade = $item['quantidade'] ?? 0;
            $precoUnitario = $item['preco_unitario'] ?? 0;

            $total += $quantidade * $precoUnitario;
        }

        $set('valor_total', number_format($total, 2, ',', '.'));
    }
}
