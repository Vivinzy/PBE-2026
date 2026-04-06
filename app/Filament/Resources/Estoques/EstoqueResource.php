<?php

namespace App\Filament\Resources\Estoques;

use App\Filament\Resources\Estoques\Pages\CreateEstoque;
use App\Filament\Resources\Estoques\Pages\EditEstoque;
use App\Filament\Resources\Estoques\Pages\ListEstoques;
use App\Filament\Resources\Estoques\Pages\ViewEstoque;
use App\Filament\Resources\Estoques\Schemas\EstoqueInfolist;
use App\Filament\Resources\Estoques\Tables\EstoquesTable;
use App\Models\Estoque;
use BackedEnum;
use UnitEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstoqueResource extends Resource
{
    protected static ?string $model = Estoque::class;

        protected static string|UnitEnum|null $navigationGroup = 'Estoque';

    // 3. A ORDEM
    //Define quem aparece primeiro. 1 é o mais alto
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Estoque';

public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
                Forms\Components\Select::make('produto_id')
                    ->relationship('produto', 'nome')
                    ->required()
                    ->label('Produto'),

                Forms\Components\TextInput::make('nome_produto')
                    ->required()
                    ->label('Nome do Produto'),

                Forms\Components\TextInput::make('quantidade')
                    ->numeric()
                    ->required()
                    ->label('Quantidade'),

                Forms\Components\TextInput::make('unidade_medida')
                    ->required()
                    ->label('Unidade de Medida'),

                Forms\Components\TextInput::make('preco')
                    ->numeric()
                    ->prefix('R$')
                    ->required()
                    ->label('Preço'),

                Forms\Components\TextInput::make('fornecedor')
                    ->required()
                    ->label('Fornecedor'),

                Forms\Components\DatePicker::make('data_entrada')
                    ->required()
                    ->label('Data de Entrada'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EstoqueInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstoquesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEstoques::route('/'),
            'create' => CreateEstoque::route('/create'),
            'view' => ViewEstoque::route('/{record}'),
            'edit' => EditEstoque::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}