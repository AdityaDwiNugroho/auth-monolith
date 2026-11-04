<?php

namespace Modules\Library\Providers;

use Illuminate\Support\ServiceProvider;

class LibraryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Memuat migrasi modul Library
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');

        // Memuat route modul Library
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
