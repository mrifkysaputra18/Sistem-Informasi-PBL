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


        // Override mail config from database if SMTP enabled
        if (class_exists(\App\Models\Setting::class)) {
            try {
                $enabled = \App\Models\Setting::get('email_smtp_enabled', false);
                
                if ($enabled === 'true' || $enabled === true) {
                    $password = \App\Models\Setting::get('email_smtp_password', '');
                    if ($password) {
                        try {
                            $password = decrypt($password);
                        } catch (\Exception $e) {
                            \Log::error('Failed to decrypt email password');
                            $password = '';
                        }
                    }

                    \Config::set('mail.default', 'smtp');
                    \Config::set('mail.mailers.smtp', [
                        'transport'  => 'smtp',
                        'host' => \App\Models\Setting::get('email_smtp_host', 'smtp.gmail.com'),
                        'port' => (int)\App\Models\Setting::get('email_smtp_port', '587'),
                        'encryption' => \App\Models\Setting::get('email_smtp_encryption', 'tls'),
                        'username' => \App\Models\Setting::get('email_smtp_username', ''),
                        'password' => $password,
                        'timeout' => null,
                    ]);

                    \Config::set('mail.from', [
                        'address' => \App\Models\Setting::get('email_from_address', config('mail.from.address')),
                        'name' => \App\Models\Setting::get('email_from_name', config('mail.from.name')),
                    ]);
                }
            } catch (\Exception $e) {
                \Log::debug('Could not load email settings: ' . $e->getMessage());
            }
        }
    }
}
