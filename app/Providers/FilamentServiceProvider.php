<?php

namespace App\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Panel::make()
            ->sidebarWidth('20rem') // Ganti dengan lebar yang diinginkan
            ->collapsedSidebarWidth('9rem'); // Ganti dengan lebar yang diinginkan saat sidebar terlipat
    }
}
