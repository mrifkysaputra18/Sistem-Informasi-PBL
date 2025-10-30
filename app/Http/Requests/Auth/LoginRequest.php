<?php

namespace App\Http\Requests\Auth;

use App\Models\Pengguna;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Auto-register untuk email Politala
        $this->autoRegisterPolitalaUser();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }
    
    /**
     * Auto-register user jika menggunakan email Politala
     */
    protected function autoRegisterPolitalaUser(): void
    {
        try {
            $email = $this->input('email');
            
            // Log untuk debugging
            \Log::info('Auto-register attempt', ['email' => $email]);
            
            // Cek apakah email Politala
            if (!$this->isPolitalaEmail($email)) {
                \Log::info('Not a Politala email');
                return;
            }
            
            // Cek apakah user sudah ada
            if (Pengguna::where('email', $email)->exists()) {
                \Log::info('User already exists');
                return;
            }
            
            // Auto-register user baru
            $role = $this->determineRole($email);
            $name = $this->extractNameFromEmail($email);
            $politalaId = $this->generatePolitalaId($email, $role);
            
            \Log::info('Creating new user', [
                'email' => $email,
                'name' => $name,
                'role' => $role,
                'politala_id' => $politalaId,
            ]);
            
            $user = Pengguna::create([
                'politala_id' => $politalaId,
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($this->input('password')),
                'role' => $role,
                'program_studi' => 'Sistem Informasi',
                'is_active' => true,
            ]);
            
            \Log::info('User created successfully', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            \Log::error('Auto-register failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
    
    /**
     * Check jika email adalah email Politala
     */
    protected function isPolitalaEmail(string $email): bool
    {
        return Str::endsWith($email, ['@politala.ac.id', '@mhs.politala.ac.id']);
    }
    
    /**
     * Tentukan role berdasarkan email
     */
    protected function determineRole(string $email): string
    {
        if (Str::endsWith($email, '@mhs.politala.ac.id')) {
            return 'mahasiswa';
        }
        
        // Check untuk email khusus admin
        if ($email === 'admin@politala.ac.id') {
            return 'admin';
        }
        
        // Check untuk email khusus koordinator
        if ($email === 'koordinator@politala.ac.id') {
            return 'koordinator';
        }
        
        // Default untuk @politala.ac.id lainnya
        return 'dosen';
    }
    
    /**
     * Extract nama dari email
     */
    protected function extractNameFromEmail(string $email): string
    {
        $username = Str::before($email, '@');
        $name = Str::replace(['.', '_', '-'], ' ', $username);
        return Str::title($name);
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
        $randomNumber = rand(100, 999);
        
        return strtoupper($prefix . '_' . Str::slug($username) . '_' . $randomNumber);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
