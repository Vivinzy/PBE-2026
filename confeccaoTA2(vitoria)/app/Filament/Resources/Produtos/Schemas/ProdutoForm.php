<?php

namespace App\Filament\Resources\Produtos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProdutoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->label('Nome do Produto')
                    ->required()
                    ->maxLength(255),

                TextInput::make('referencia')
                    ->label('Referência')
                    ->placeholder('Ex: CAM-PRE-001')
                    ->maxLength(100),

                TextInput::make('preco_venda')
                    ->label('Preço de Venda')
                    ->numeric()
                    ->prefix('R$')
                    ->minValue(0),

                TextInput::make('estoque')
                    ->label('Estoque')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }
}