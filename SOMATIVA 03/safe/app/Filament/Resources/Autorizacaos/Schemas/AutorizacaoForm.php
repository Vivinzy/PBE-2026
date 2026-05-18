<?php

namespace App\Filament\Resources\Autorizacaos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class AutorizacaoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('aluno_nome')->required(),
                TextInput::make('responsavel_nome')->required(),
                Select::make('status')
                    ->options([
                        'pendente' => 'Pendente',
                        'aprovado' => 'Aprovado',
                        'negado' => 'Negado',
                    ])
                    ->required(),
                DateTimePicker::make('data_saida'),
            ]);
    }
}