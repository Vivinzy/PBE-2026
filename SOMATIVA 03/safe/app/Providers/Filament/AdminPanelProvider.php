<?php

namespace App\Providers\Filament;

use App\Filament\Pages\PortariaPage;
use App\Filament\Resources\AlunoResource;
use App\Filament\Resources\RegistroMovimentoResource;
use App\Filament\Resources\TurmaResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('safe')
            ->path('safe')
            ->login()

            // Habilitar registro de novos usuários com campos customizados
            ->registration(\App\Filament\Pages\Auth\Register::class)

            ->colors([
                'primary' => Color::Blue,
            ])

            ->brandName('SAFE — Sistema de Fluxo Escolar')
            ->brandLogo(null)

            ->pages([
                Pages\Dashboard::class,
                PortariaPage::class,
            ])

            ->resources([
                AlunoResource::class,
                RegistroMovimentoResource::class,
                TurmaResource::class,
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}