<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\Pengguna;
use App\Mail\PasswordResetOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Show the request OTP form
     */
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
        ]);

        // Rate limiting: 3 attempts per hour
        $key = 'send-otp:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan. Coba lagi dalam {$minutes} menit.",
            ]);
        }

        // Check if email exists
        $user = Pengguna::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak terdaftar dalam sistem.',
            ]);
        }

        // Generate OTP
        $otp = PasswordResetOtp::generate($request->email);

        // Send email
        try {
            Mail::to($request->email)->send(new PasswordResetOtpMail(
                $otp->otp_code,
                '10 menit'
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi.');
        }

        // Increment rate limiter
        RateLimiter::hit($key, 3600); // 1 hour

        // Store email in session
        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.otp.verify')
            ->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
    }

    /**
     * Show the OTP verification form
     */
    public function showVerifyForm()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.otp.request')
                ->with('error', 'Sesi telah berakhir. Silakan request OTP kembali.');
        }

        return view('auth.forgot-password-verify');
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ], [
            'otp_code.required' => 'Kode OTP wajib diisi',
            'otp_code.size' => 'Kode OTP harus 6 digit',
        ]);

        $email = session('password_reset_email');
        
        if (!$email) {
            return redirect()->route('password.otp.request')
                ->with('error', 'Sesi telah berakhir. Silakan request OTP kembali.');
        }

        $otp = PasswordResetOtp::verify($email, $request->otp_code);

        if (!$otp) {
            throw ValidationException::withMessages([
                'otp_code' => 'Kode OTP tidak valid atau telah kadaluarsa.',
            ]);
        }

        // Store OTP ID in session for password reset
        session(['password_reset_otp_id' => $otp->id]);

        return redirect()->route('password.otp.reset')
            ->with('success', 'Kode OTP berhasil diverifikasi. Silakan masukkan password baru Anda.');
    }

    /**
     * Show the reset password form
     */
    public function showResetForm()
    {
        if (!session('password_reset_otp_id') || !session('password_reset_email')) {
            return redirect()->route('password.otp.request')
                ->with('error', 'Sesi telah berakhir. Silakan request OTP kembali.');
        }

        return view('auth.forgot-password-reset');
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $email = session('password_reset_email');
        $otpId = session('password_reset_otp_id');

        if (!$email || !$otpId) {
            return redirect()->route('password.otp.request')
                ->with('error', 'Sesi telah berakhir. Silakan request OTP kembali.');
        }

        // Verify OTP exists and is verified
        $otp = PasswordResetOtp::find($otpId);
        
        if (!$otp || !$otp->isVerified() || $otp->email !== $email) {
            return redirect()->route('password.otp.request')
                ->with('error', 'OTP tidak valid. Silakan request OTP kembali.');
        }

        // Update user password
        $user = Pengguna::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('password.otp.request')
                ->with('error', 'User tidak ditemukan.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete OTP
        $otp->delete();

        // Clear session
        session()->forget(['password_reset_email', 'password_reset_otp_id']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        // Use same sendOtp logic but bypass initial validation
        $request->merge(['email' => $email]);
        return $this->sendOtp($request);
    }
}
