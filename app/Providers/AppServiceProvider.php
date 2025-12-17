<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Models\RuangKelas;
use App\Models\TargetMingguan;
use App\Policies\TargetMingguanPolicy;

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
            return RuangKelas::findOrFail($value);
        });

        // Register Policy untuk TargetMingguan
        Gate::policy(TargetMingguan::class, TargetMingguanPolicy::class);
    }
}
