<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'rn_re',
        'name',
        'email',
        'password',
        'role',
        'ativo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'  => 'hashed',
        'ativo'     => 'boolean',
    ];

    // Filament: define quem pode acessar o painel
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->ativo;
    }

    // Relacionamentos
    public function turmas()
    {
        return $this->hasMany(Turma::class, 'professor_id');
    }

    public function registros()
    {
        return $this->hasMany(RegistroMovimento::class, 'registrado_por');
    }

    // Helpers de role
    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    public function isCoordenacao(): bool
    {
        return $this->role === 'coordenacao';
    }

    public function isPortaria(): bool
    {
        return $this->role === 'portaria';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}