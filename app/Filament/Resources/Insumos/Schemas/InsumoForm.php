<?php

namespace App\Filament\Resources\Insumos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InsumoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->label('Nome do Insumo')
                    ->required()
                    ->maxLength(255),

                Select::make('unidade_medida')
                    ->label('Unidade de Medida')
                    ->required()
                    ->options([
                        'un'  => 'Unidade (un)',
                        'kg'  => 'Quilograma (kg)',
                        'g'   => 'Grama (g)',
                        'l'   => 'Litro (l)',
                        'ml'  => 'Mililitro (ml)',
                        'm'   => 'Metro (m)',
                        'cm'  => 'Centímetro (cm)',
                        'cx'  => 'Caixa (cx)',
                        'pct' => 'Pacote (pct)',
                        'rol' => 'Rolo (rol)',
                    ]),

                TextInput::make('preco_custo')
                    ->label('Preço de Custo')
                    ->numeric()
                    ->prefix('R$')
                    ->minValue(0),

                TextInput::make('estoque')
                    ->label('Estoque')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->minValue(0),
            ]);
    }
}