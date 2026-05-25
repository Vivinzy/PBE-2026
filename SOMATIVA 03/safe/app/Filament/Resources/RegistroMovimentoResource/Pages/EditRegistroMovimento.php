<?php

namespace App\Filament\Resources\RegistroMovimentoResource\Pages;

use App\Filament\Resources\RegistroMovimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistroMovimento extends EditRecord
{
    protected static string $resource = RegistroMovimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}