<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\ClassRoom;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Fast Excel auto-registers via Laravel package discovery
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Explicit route model binding untuk ClassRoom
        Route::bind('classroom', function ($value) {
            return ClassRoom::findOrFail($value);
        });
    }
}
