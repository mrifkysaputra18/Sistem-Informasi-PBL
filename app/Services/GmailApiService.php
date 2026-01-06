<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class GmailApiService
{
    protected $client;
    
    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google_drive.oauth.client_id'));
        $this->client->setClientSecret(config('services.google_drive.oauth.client_secret'));
        $this->client->addScope(Gmail::GMAIL_SEND);
        
        $this->loadTokenFromDatabase();
    }
    
    protected function loadTokenFromDatabase()
    {
        $accessToken = Setting::get('gmail_oauth_access_token');
        $refreshToken = Setting::get('gmail_oauth_refresh_token');
        
        if ($accessToken && $refreshToken) {
            try {
                $this->client->setAccessToken([
                    'access_token' => decrypt($accessToken),
                    'refresh_token' => decrypt($refreshToken),
                    'expires_in' => 3600,
                ]);
                
                if ($this->client->isAccessTokenExpired()) {
                    $this->refreshAccessToken();
                }
            } catch (\Exception $e) {
                Log::error('Failed to load Gmail OAuth token: ' . $e->getMessage());
            }
        }
    }
    
    protected function refreshAccessToken()
    {
        try {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken();
            
            if (isset($newToken['error'])) {
                throw new \Exception($newToken['error']);
            }
            
            Setting::set('gmail_oauth_access_token', encrypt($newToken['access_token']), 'encrypted', 'gmail', 'Gmail Access Token');
            Setting::set('gmail_oauth_token_expires_at', now()->addSeconds($newToken['expires_in'])->toDateTimeString(), 'string', 'gmail', 'Token Expiry');
        } catch (\Exception $e) {
            Log::error('Failed to refresh Gmail token: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function isConfigured(): bool
    {
        return !empty(Setting::get('gmail_oauth_access_token')) && 
               !empty(Setting::get('gmail_oauth_refresh_token'));
    }
    
    public function sendEmail(string $to, string $subject, string $htmlBody, ?string $fromName = null, ?string $fromEmail = null)
    {
        if (!$this->isConfigured()) {
            throw new \Exception('Gmail API not configured');
        }
        
        $fromName = $fromName ?? Setting::get('email_from_name', 'SMART PBL');
        $fromEmail = $fromEmail ?? Setting::get('email_from_address', Setting::get('gmail_oauth_email'));
        
        $message = new Message();
        $rawMessage = $this->createMimeMessage($to, $fromEmail, $fromName, $subject, $htmlBody);
        $message->setRaw(base64_encode($rawMessage));
        
        $service = new Gmail($this->client);
        $service->users_messages->send('me', $message);
    }
    
    protected function createMimeMessage($to, $from, $fromName, $subject, $htmlBody)
    {
        $from = "$fromName <$from>";
        
        $mime = "From: $from\r\n";
        $mime .= "To: $to\r\n";
        $mime .= "Subject: $subject\r\n";
        $mime .= "MIME-Version: 1.0\r\n";
        $mime .= "Content-Type: text/html; charset=utf-8\r\n";
        $mime .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
        $mime .= quoted_printable_encode($htmlBody);
        
        return $mime;
    }
    
    public function testConnection(): array
    {
        try {
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'error' => 'Gmail belum terhubung',
                ];
            }
            
            $service = new Gmail($this->client);
            $profile = $service->users->getProfile('me');
            
            return [
                'success' => true,
                'email' => $profile->emailAddress,
                'messages_total' => $profile->messagesTotal,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
