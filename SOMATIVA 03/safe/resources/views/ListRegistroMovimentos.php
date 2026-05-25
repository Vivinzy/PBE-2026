<?php
// ListRegistroMovimentos.php
namespace App\Filament\Resources\RegistroMovimentoResource\Pages;

use App\Filament\Resources\RegistroMovimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistroMovimentos extends ListRecords
{
    protected static string $resource = RegistroMovimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Novo registro'),
        ];
    }
}