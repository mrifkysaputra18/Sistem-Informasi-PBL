<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;

class SsoPasswordResetController extends Controller
{
    /**
     * Show SSO verification page for password reset
     */
    public function showVerifyPage()
    {
        return view('auth.reset-password-sso-verify');
    }

    /**
     * Redirect to Google for password reset verification
     */
    public function redirectToGoogle()
    {
        $redirectUri = url('/auth/password-reset/callback');
        
        return Socialite::driver('google')
            ->redirectUrl($redirectUri)
            ->with(['hd' => 'politala.ac.id'])
            ->redirect();
    }

    /**
     * Handle SSO callback and show password reset form
     */
    public function handleCallback()
    {
        try {
            $redirectUri = url('/auth/password-reset/callback');
            
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();
            
            // Find user by email
            $user = Pengguna::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                return redirect()->route('password.sso.verify')
                    ->with('error', 'Akun tidak ditemukan. Silakan hubungi admin.');
            }
            
            // Store verified email in session
            session(['password_reset_verified_email' => $user->email]);
            session(['password_reset_verified_at' => now()]);
            
            return view('auth.reset-password-sso-form', ['email' => $user->email]);
            
        } catch (\Exception $e) {
            \Log::error('SSO Password Reset Error', ['error' => $e->getMessage()]);
            
            return redirect()->route('password.sso.verify')
                ->with('error', 'Gagal verifikasi SSO. Silakan coba lagi.');
        }
    }

    /**
     * Update password after SSO verification
     */
    public function updatePassword(Request $request)
    {
        // Verify session
        $verifiedEmail = session('password_reset_verified_email');
        $verifiedAt = session('password_reset_verified_at');
        
        if (!$verifiedEmail || !$verifiedAt) {
            return redirect()->route('password.sso.verify')
                ->with('error', 'Sesi verifikasi telah berakhir. Silakan verifikasi ulang.');
        }
        
        // Check session expiry (15 minutes)
        if (now()->diffInMinutes($verifiedAt) > 15) {
            session()->forget(['password_reset_verified_email', 'password_reset_verified_at']);
            return redirect()->route('password.sso.verify')
                ->with('error', 'Sesi verifikasi telah berakhir. Silakan verifikasi ulang.');
        }
        
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user = Pengguna::where('email', $verifiedEmail)->first();
        
        if (!$user) {
            return redirect()->route('password.sso.verify')
                ->with('error', 'User tidak ditemukan.');
        }
        
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Clear session
        session()->forget(['password_reset_verified_email', 'password_reset_verified_at']);
        
        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
    }
}
