<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('rn_re')
                    ->label('RN / RE')
                    ->required()
                    ->unique('users', 'rn_re')
                    ->maxLength(20)
                    ->placeholder('Ex: RN2024001 ou RE2023045'),

                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),

                Select::make('role')
                    ->label('Perfil de acesso')
                    ->options([
                        'professor'   => 'Professor',
                        'coordenacao' => 'Coordenação',
                        'portaria'    => 'Portaria',
                    ])
                    ->required()
                    ->default('professor'),

                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    /**
     * Inclui rn_re e role ao criar o usuário.
     */
    protected function handleRegistration(array $data): Model
    {
        return $this->getUserModel()::create([
            'rn_re'    => $data['rn_re'],
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => $data['role'],
            'ativo'    => true,
        ]);
    }
}