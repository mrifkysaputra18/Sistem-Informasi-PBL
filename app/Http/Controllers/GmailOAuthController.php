<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Google\Client;
use Google\Service\Gmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GmailOAuthController extends Controller
{
    public function redirect()
    {
        $client = new Client();
        $client->setClientId(config('services.google_drive.oauth.client_id'));
        $client->setClientSecret(config('services.google_drive.oauth.client_secret'));
        $client->setRedirectUri(route('settings.gmail.callback'));
        $client->addScope(Gmail::GMAIL_SEND);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        
        return redirect($client->createAuthUrl());
    }
    
    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()->route('settings.email.index')
                ->with('error', 'OAuth dibatalkan atau gagal');
        }
        
        try {
            $client = new Client();
            $client->setClientId(config('services.google_drive.oauth.client_id'));
            $client->setClientSecret(config('services.google_drive.oauth.client_secret'));
            $client->setRedirectUri(route('settings.gmail.callback'));
            
            $token = $client->fetchAccessTokenWithAuthCode($request->code);
            
            if (isset($token['error'])) {
                return redirect()->route('settings.email.index')
                    ->with('error', 'OAuth failed: ' . $token['error']);
            }
            
            // Get user info
            $client->setAccessToken($token);
            $service = new Gmail($client);
            $profile = $service->users->getProfile('me');
            
            // Save to database
            Setting::set('gmail_oauth_enabled', 'true', 'boolean', 'gmail', 'Gmail OAuth Enabled');
            Setting::set('gmail_oauth_access_token', encrypt($token['access_token']), 'encrypted', 'gmail', 'Access Token');
            Setting::set('gmail_oauth_refresh_token', encrypt($token['refresh_token'] ?? ''), 'encrypted', 'gmail', 'Refresh Token');
            Setting::set('gmail_oauth_token_expires_at', now()->addSeconds($token['expires_in'])->toDateTimeString(), 'string', 'gmail', 'Token Expiry');
            Setting::set('gmail_oauth_email', $profile->emailAddress, 'string', 'gmail', 'Gmail Email');
            
            // Set default from address if not set
            if (empty(Setting::get('email_from_address'))) {
                Setting::set('email_from_address', $profile->emailAddress, 'string', 'email', 'Email From');
            }
            
            Cache::forget('mail_config');
            
            return redirect()->route('settings.email.index')
                ->with('success', 'Gmail berhasil terhubung! Email: ' . $profile->emailAddress);
        } catch (\Exception $e) {
            return redirect()->route('settings.email.index')
                ->with('error', 'Gagal menghubungkan Gmail: ' . $e->getMessage());
        }
    }
    
    public function disconnect(Request $request)
    {
        Setting::set('gmail_oauth_enabled', 'false', 'boolean', 'gmail', 'Gmail OAuth Enabled');
        Setting::forget('gmail_oauth_access_token');
        Setting::forget('gmail_oauth_refresh_token');
        Setting::forget('gmail_oauth_token_expires_at');
        Setting::forget('gmail_oauth_email');
        
        Cache::forget('mail_config');
        
        return redirect()->route('settings.email.index')
            ->with('success', 'Gmail berhasil diputuskan!');
    }
}
