<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetOtp extends Model
{
    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Check if OTP has been verified
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * Mark OTP as verified
     */
    public function markAsVerified(): void
    {
        $this->update(['verified_at' => Carbon::now()]);
    }

    /**
     * Generate new OTP for email
     */
    public static function generate(string $email): self
    {
        // Delete old OTPs for this email
        self::where('email', $email)->delete();

        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Create new OTP with 10-minute expiration
        return self::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
    }

    /**
     * Verify OTP code for email
     */
    public static function verify(string $email, string $code): ?self
    {
        $otp = self::where('email', $email)
            ->where('otp_code', $code)
            ->whereNull('verified_at')
            ->first();

        if (!$otp || $otp->isExpired()) {
            return null;
        }

        $otp->markAsVerified();
        return $otp;
    }

    /**
     * Clean up expired OTPs (for scheduler)
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', Carbon::now()->subHours(24))->delete();
    }
}
