<?php

namespace App\Providers;

use App\Models\RegistroMovimento;
use App\Observers\RegistroMovimentoObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RegistroMovimento::observe(RegistroMovimentoObserver::class);
    }
}