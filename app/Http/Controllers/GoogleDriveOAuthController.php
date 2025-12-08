<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Controller untuk OAuth Google Drive
 * Mengelola autentikasi dan koneksi akun Google Drive
 */
class GoogleDriveOAuthController extends Controller
{
    protected $klien;

    public function __construct()
    {
        $this->klien = new Client();
        $this->klien->setClientId(config('services.google_drive.oauth.client_id'));
        $this->klien->setClientSecret(config('services.google_drive.oauth.client_secret'));
        $this->klien->setRedirectUri(config('services.google_drive.oauth.redirect'));
        $this->klien->setAccessType('offline');
        $this->klien->setPrompt('consent');
        $this->klien->setScopes([
            Drive::DRIVE,
            Drive::DRIVE_FILE,
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ]);
    }

    /**
     * Arahkan ke halaman persetujuan OAuth Google
     */
    public function redirect()
    {
        $urlOtentikasi = $this->klien->createAuthUrl();
        return redirect($urlOtentikasi);
    }

    /**
     * Tangani callback dari OAuth Google
     */
    public function callback(Request $request)
    {
        // Cek apakah ada error dari Google
        if ($request->has('error')) {
            return redirect()->route('settings.google-drive.index')
                ->with('error', 'Gagal menghubungkan Google Drive: ' . $request->get('error'));
        }

        $kode = $request->get('code');
        
        // Validasi kode otorisasi
        if (!$kode) {
            return redirect()->route('settings.google-drive.index')
                ->with('error', 'Kode otorisasi tidak ditemukan');
        }

        try {
            // Tukar kode dengan token akses
            $token = $this->klien->fetchAccessTokenWithAuthCode($kode);
            
            if (isset($token['error'])) {
                throw new \Exception($token['error_description'] ?? $token['error']);
            }

            $this->klien->setAccessToken($token);

            // Ambil informasi pengguna
            $layananOauth = new \Google\Service\Oauth2($this->klien);
            $infoPengguna = $layananOauth->userinfo->get();

            // Buat folder sistem di Google Drive
            $layananDrive = new Drive($this->klien);
            $idFolder = $this->buatFolderSistem($layananDrive);

            // Simpan kredensial ke pengaturan
            Setting::set('google_drive_oauth_token', json_encode($token), 'json', 'google_drive', 'Token Akses OAuth');
            Setting::set('google_drive_oauth_email', $infoPengguna->email, 'string', 'google_drive', 'Email Akun Google Terhubung');
            Setting::set('google_drive_oauth_name', $infoPengguna->name, 'string', 'google_drive', 'Nama Akun Google Terhubung');
            Setting::set('google_drive_folder_id', $idFolder, 'string', 'google_drive', 'ID Folder Utama');
            Setting::set('google_drive_auth_type', 'oauth', 'string', 'google_drive', 'Tipe Autentikasi');
            Setting::set('google_drive_enabled', 'true', 'boolean', 'google_drive', 'Google Drive Aktif');

            // Bersihkan cache
            Cache::forget('setting_google_drive_oauth_token');
            Cache::forget('setting_google_drive_folder_id');
            Cache::forget('setting_google_drive_auth_type');

            return redirect()->route('settings.google-drive.index')
                ->with('success', 'Berhasil menghubungkan Google Drive dengan akun ' . $infoPengguna->email);

        } catch (\Exception $e) {
            Log::error('Kesalahan callback OAuth Google Drive', [
                'pesan' => $e->getMessage(),
            ]);

            return redirect()->route('settings.google-drive.index')
                ->with('error', 'Gagal menghubungkan Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Buat folder sistem di Google Drive
     * 
     * @param Drive $layananDrive Instance layanan Google Drive
     * @return string ID folder yang dibuat atau ditemukan
     */
    protected function buatFolderSistem(Drive $layananDrive): string
    {
        $namaFolder = 'Sistem-Informasi-PBL';
        
        // Cek apakah folder sudah ada
        $respons = $layananDrive->files->listFiles([
            'q' => "name='{$namaFolder}' and mimeType='application/vnd.google-apps.folder' and trashed=false",
            'spaces' => 'drive',
            'fields' => 'files(id, name)',
        ]);

        $daftarFile = $respons->getFiles();
        
        // Jika folder sudah ada, kembalikan ID-nya
        if (count($daftarFile) > 0) {
            return $daftarFile[0]->getId();
        }

        // Buat folder baru
        $metadataFile = new \Google\Service\Drive\DriveFile([
            'name' => $namaFolder,
            'mimeType' => 'application/vnd.google-apps.folder',
            'description' => 'Folder untuk menyimpan file progress mahasiswa dari Sistem Informasi PBL',
        ]);

        $folder = $layananDrive->files->create($metadataFile, [
            'fields' => 'id',
        ]);

        return $folder->getId();
    }

    /**
     * Putuskan koneksi akun Google Drive
     */
    public function disconnect()
    {
        try {
            // Cabut token
            $tokenJson = Setting::get('google_drive_oauth_token');
            if ($tokenJson) {
                $token = is_string($tokenJson) ? json_decode($tokenJson, true) : $tokenJson;
                if (isset($token['access_token'])) {
                    $this->klien->revokeToken($token['access_token']);
                }
            }
        } catch (\Exception $e) {
            // Abaikan error pencabutan token
            Log::warning('Gagal mencabut token OAuth', ['pesan' => $e->getMessage()]);
        }

        // Hapus pengaturan OAuth
        Setting::where('key', 'like', 'google_drive_oauth%')->delete();
        Setting::set('google_drive_auth_type', 'service_account', 'string', 'google_drive', 'Tipe Autentikasi');
        Setting::set('google_drive_folder_id', '', 'string', 'google_drive', 'ID Folder Utama');

        // Bersihkan cache
        Cache::forget('setting_google_drive_oauth_token');
        Cache::forget('setting_google_drive_oauth_email');
        Cache::forget('setting_google_drive_oauth_name');
        Cache::forget('setting_google_drive_folder_id');
        Cache::forget('setting_google_drive_auth_type');

        return redirect()->route('settings.google-drive.index')
            ->with('success', 'Akun Google Drive berhasil diputuskan');
    }

    /**
     * Ambil klien Google Drive dengan token valid
     * 
     * @return Client|null Klien Google yang terotentikasi atau null
     */
    public static function ambilKlienTerotentikasi(): ?Client
    {
        $tokenJson = Setting::get('google_drive_oauth_token');
        
        if (!$tokenJson) {
            return null;
        }

        // Konversi token ke array jika masih string
        $token = is_string($tokenJson) ? json_decode($tokenJson, true) : $tokenJson;
        
        $klien = new Client();
        $klien->setClientId(config('services.google_drive.oauth.client_id'));
        $klien->setClientSecret(config('services.google_drive.oauth.client_secret'));
        $klien->setAccessToken($token);

        // Cek apakah token kedaluwarsa dan perbarui jika perlu
        if ($klien->isAccessTokenExpired()) {
            if (isset($token['refresh_token'])) {
                $tokenBaru = $klien->fetchAccessTokenWithRefreshToken($token['refresh_token']);
                
                // Pertahankan refresh token jika tidak dikembalikan
                if (!isset($tokenBaru['refresh_token']) && isset($token['refresh_token'])) {
                    $tokenBaru['refresh_token'] = $token['refresh_token'];
                }

                $klien->setAccessToken($tokenBaru);
                
                // Simpan token baru
                Setting::set('google_drive_oauth_token', json_encode($tokenBaru), 'json', 'google_drive', 'Token Akses OAuth');
                Cache::forget('setting_google_drive_oauth_token');
            } else {
                return null;
            }
        }

        return $klien;
    }

    /**
     * Alias untuk kompatibilitas dengan kode lama
     */
    public static function getAuthenticatedClient(): ?Client
    {
        return self::ambilKlienTerotentikasi();
    }
}
