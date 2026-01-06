<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

            // Cari user berdasarkan email
            $user = Pengguna::where('email', $googleUser->getEmail())->first();
            $isFirstTimeLogin = false;

            if (!$user) {
                // User belum ada sama sekali → Auto-create account
                $plainPassword = $this->generateRandomPassword();
                $user = $this->createUserFromGoogle($googleUser, $plainPassword);
                $isFirstTimeLogin = true;
                
                // Kirim email dengan kredensial
                $this->sendAccountCreatedEmail($user, $plainPassword);
            } elseif ($user->password === null) {
                // User sudah ada (dari import) tapi belum pernah login SSO
                // Generate password untuk first-time SSO login
                $plainPassword = $this->generateRandomPassword();
                $user->password = Hash::make($plainPassword);
                $user->email_verified_at = now();
                $user->save();
                $isFirstTimeLogin = true;
                
                // Kirim email dengan kredensial
                try {
                    Mail::to($user->email)->send(new \App\Mail\AccountCreated($user, $plainPassword));
                    \Log::info('First-time SSO login email sent', ['user_id' => $user->id]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send first-time SSO email', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            // Else: User sudah pernah login SSO, password sudah ada → Skip semua

            // Cek apakah akun aktif
            if (!$user->is_active) {
                return redirect()->route('login')
                    ->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
            }

            // Login user
            Auth::login($user, true);

            $welcomeMessage = $isFirstTimeLogin 
                ? 'Selamat datang, ' . $user->name . '! Password telah dikirim ke email Anda.'
                : 'Selamat datang kembali, ' . $user->name . '!';

            return redirect()->route('dashboard')
                ->with('success', $welcomeMessage);

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
    protected function createUserFromGoogle($googleUser, string $plainPassword)
    {
        $email = $googleUser->getEmail();
        $role = $this->determineRole($email);
        $politalaId = $this->generatePolitalaId($email, $role);
        $fullName = $googleUser->getName(); // "2401301068 MUHAMMAD RAIHAN" format

        // Parse NIM dan nama untuk mahasiswa
        $nim = null;
        $name = $fullName;
        
        if ($role === 'mahasiswa') {
            // Extract NIM (angka di awal) dan nama (sisanya)
            if (preg_match('/^(\d+)\s+(.+)$/', $fullName, $matches)) {
                $nim = $matches[1]; // "2401301068"
                $name = $matches[2]; // "MUHAMMAD RAIHAN"
            }
        }

        return Pengguna::create([
            'politala_id' => $politalaId,
            'nim' => $nim, // NIM di field yang benar
            'name' => $name, // Nama tanpa NIM
            'email' => $email,
            'email_verified_at' => now(), // Email sudah verified by Google
            'password' => Hash::make($plainPassword), // Use provided password
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
     * Extract program studi from email (default: Teknologi Informasi)
     */
    protected function extractProgramStudi(string $email): string
    {
        // Bisa dikustomisasi berdasarkan email pattern jika ada
        return 'Teknologi Informasi';
    }

    /**
     * Generate secure random password
     */
    protected function generateRandomPassword(): string
    {
        // Generate 12-character password with letters, numbers, and symbols
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%';
        
        $password = '';
        // Ensure at least one of each type
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];
        
        // Fill remaining 8 characters
        $all = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 0; $i < 8; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }
        
        // Shuffle to randomize positions
        return str_shuffle($password);
    }
    
    /**
     * Send account created email via Gmail API or SMTP
     */
    protected function sendAccountCreatedEmail($user, $plainPassword)
    {
        try {
            Mail::to($user->email)->send(new \App\Mail\AccountCreated($user, $plainPassword));
            \Log::info('Account created email sent via SMTP', ['email' => $user->email]);
        } catch (\Exception $e) {
            \Log::error('Failed to send account created email', [
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
        }
    }
}