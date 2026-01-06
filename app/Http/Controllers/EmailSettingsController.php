<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class EmailSettingsController extends Controller
{
    /**
     * Show email settings page
     */
    public function index()
    {
        $gmailEnabled = Setting::get('gmail_oauth_enabled', 'false') === 'true';
        $gmailEmail = Setting::get('gmail_oauth_email', '');
        
        return view('settings.email', [
            // Gmail OAuth
            'gmailEnabled' => $gmailEnabled,
            'gmailEmail' => $gmailEmail,
            
            // Email display settings
            'fromAddress' => Setting::get('email_from_address', config('mail.from.address')),
            'fromName' => Setting::get('email_from_name', config('mail.from.name')),
            
            // Legacy SMTP (fallback)
            'enabled' => Setting::get('email_smtp_enabled', false),
            'host' => Setting::get('email_smtp_host', 'smtp.gmail.com'),
            'port' => Setting::get('email_smtp_port', '587'),
            'encryption' => Setting::get('email_smtp_encryption', 'tls'),
            'username' => Setting::get('email_smtp_username', ''),
            'hasPassword' => !empty(Setting::get('email_smtp_password', '')),
        ]);
    }

    /**
     * Update email settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|email|max:255',
            'password' => 'nullable|string|min:8',
            'from_name' => 'required|string|max:255',
        ], [
            'username.required' => 'Akun Gmail wajib diisi',
            'username.email' => 'Format email tidak valid',
            'password.min' => 'App Password minimal 8 karakter',
            'from_name.required' => 'Nama pengirim wajib diisi',
        ]);


        // Save all settings - Auto-enable SMTP and hardcode Gmail config
        Setting::set('email_smtp_enabled', 'true', 'boolean', 'email', 'Aktifkan SMTP');
        Setting::set('email_smtp_host', 'smtp.gmail.com', 'string', 'email', 'SMTP Host');
        Setting::set('email_smtp_port', '587', 'string', 'email', 'SMTP Port');
        Setting::set('email_smtp_encryption', 'tls', 'string', 'email', 'Enkripsi');
        Setting::set('email_smtp_username', $validated['username'], 'string', 'email', 'Akun Gmail');
        Setting::set('email_from_address', $validated['username'], 'string', 'email', 'Email From'); // Auto from Gmail account
        Setting::set('email_from_name', $validated['from_name'], 'string', 'email', 'Nama Pengirim');

        // Only update password if provided
        if (!empty($validated['password'])) {
            Setting::set('email_smtp_password', encrypt($validated['password']), 'encrypted', 'email', 'Password SMTP');
        }

        // Clear config cache
        Cache::forget('mail_config');
        
        return redirect()->route('settings.email.index')
            ->with('success', 'Pengaturan email berhasil disimpan!');
    }

    /**
     * Test email connection (AJAX)
     */
    public function testConnection(Request $request)
    {
        try {
            $testEmail = $request->input('test_email', auth()->user()->email);
            
            // Temporarily configure mail with current settings
            $this->configureMailFromSettings();
            
            // Send test email
            Mail::raw('Test email dari SMART PBL. Jika Anda menerima email ini, konfigurasi SMTP berhasil!', function($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('Test Email - SMART PBL');
            });

            return response()->json([
                'success' => true,
                'message' => 'Email test berhasil dikirim ke ' . $testEmail . '. Silakan cek inbox Anda.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengirim email: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Configure mail settings from database
     */
    private function configureMailFromSettings()
    {
        $password = Setting::get('email_smtp_password', '');
        if ($password) {
            try {
                $password = decrypt($password);
            } catch (\Exception $e) {
                throw new \Exception('Password tidak valid atau corrupt');
            }
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => Setting::get('email_smtp_host', 'smtp.gmail.com'),
            'port' => (int)Setting::get('email_smtp_port', '587'),
            'encryption' => Setting::get('email_smtp_encryption', 'tls'),
            'username' => Setting::get('email_smtp_username', ''),
            'password' => $password,
            'timeout' => null,
        ]);

        Config::set('mail.from', [
            'address' => Setting::get('email_from_address', 'noreply@example.com'),
            'name' => Setting::get('email_from_name', 'SMART PBL'),
        ]);
    }
    
    /**
     * Test Gmail API connection (AJAX)
     */
    public function testGmailConnection(Request $request)
    {
        try {
            $gmailService = new \App\Services\GmailApiService();
            
            if (!$gmailService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Gmail belum terhubung',
                ]);
            }
            
            $testEmail = $request->input('test_email', auth()->user()->email);
            
            $gmailService->sendEmail(
                $testEmail,
                'Test Email - SMART PBL',
                '<p>Test email dari SMART PBL via Gmail API.</p><p>Jika Anda menerima email ini, konfigurasi Gmail API berhasil!</p>',
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Email test berhasil dikirim ke ' . $testEmail . ' via Gmail API',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengirim via Gmail API: ' . $e->getMessage(),
            ]);
        }
    }
}
