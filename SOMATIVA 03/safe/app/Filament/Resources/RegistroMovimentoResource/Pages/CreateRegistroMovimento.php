<?php

namespace App\Filament\Resources\RegistroMovimentoResource\Pages;

use App\Filament\Resources\RegistroMovimentoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateRegistroMovimento extends CreateRecord
{
    protected static string $resource = RegistroMovimentoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registrado_por'] = Auth::id();
        $data['status']         = 'pendente';
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}