<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        // Dynamic redirect URI based on current request
        $redirectUri = url('/auth/google/callback');
        
        return Socialite::driver('google')
            ->redirectUrl($redirectUri)
            ->with(['hd' => 'politala.ac.id']) // Hanya email @politala.ac.id dan subdomain
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Dynamic redirect URI for multiple ports support
            $redirectUri = url('/auth/google/callback');
            
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();
            
            // Validasi email harus dari domain Politala
            if (!$this->isPolitalaEmail($googleUser->getEmail())) {
                return redirect()->route('login')
                    ->with('error', 'Hanya email Politala yang diperbolehkan! (@politala.ac.id atau @mhs.politala.ac.id)');
            }

            // Cari atau buat user
            $user = Pengguna::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Auto-register user baru dari Google
                $user = $this->createUserFromGoogle($googleUser);
            }

            // Login user
            Auth::login($user, true);

            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('error', 'Gagal login dengan Google. Silakan coba lagi atau hubungi admin.');
        }
    }

    /**
     * Create user from Google data
     */
    protected function createUserFromGoogle($googleUser)
    {
        $email = $googleUser->getEmail();
        $role = $this->determineRole($email);
        $politalaId = $this->generatePolitalaId($email, $role);

        return Pengguna::create([
            'politala_id' => $politalaId,
            'name' => $googleUser->getName(),
            'email' => $email,
            'email_verified_at' => now(), // Email sudah verified by Google
            'password' => Hash::make(Str::random(32)), // Random password (tidak digunakan)
            'role' => $role,
            'program_studi' => $this->extractProgramStudi($email),
            'is_active' => true,
        ]);
    }

    /**
     * Check if email is from Politala domain
     */
    protected function isPolitalaEmail(string $email): bool
    {
        return Str::endsWith($email, ['@politala.ac.id', '@mhs.politala.ac.id']);
    }

    /**
     * Determine role based on email domain
     */
    protected function determineRole(string $email): string
    {
        if (Str::endsWith($email, '@mhs.politala.ac.id')) {
            return 'mahasiswa';
        }
        
        // Default untuk @politala.ac.id
        return 'dosen';
    }

    /**
     * Generate Politala ID
     */
    protected function generatePolitalaId(string $email, string $role): string
    {
        $prefix = match($role) {
            'mahasiswa' => 'MHS',
            'dosen' => 'DSN',
            'koordinator' => 'KOORD',
            'admin' => 'ADMIN',
            default => 'USR',
        };
        
        $username = Str::before($email, '@');
        $randomNumber = rand(1000, 9999);
        
        return strtoupper($prefix . '_' . Str::slug($username) . '_' . $randomNumber);
    }

    /**
     * Extract program studi from email (default: Sistem Informasi)
     */
    protected function extractProgramStudi(string $email): string
    {
        // Bisa dikustomisasi berdasarkan email pattern jika ada
        return 'Teknik Informatika';
    }
}
