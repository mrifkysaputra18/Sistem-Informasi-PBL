<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Controller Pengaturan Google Drive
 * Mengelola halaman pengaturan dan koneksi Google Drive
 */
class GoogleDriveSettingsController extends Controller
{
    protected $layananGoogleDrive;

    public function __construct(GoogleDriveService $layananGoogleDrive)
    {
        $this->layananGoogleDrive = $layananGoogleDrive;
    }

    /**
     * Tampilkan halaman pengaturan Google Drive
     */
    public function index()
    {
        // Ambil pengaturan saat ini
        $idFolder = Setting::get('google_drive_folder_id', config('services.google_drive.folder_id'));
        $aktif = Setting::get('google_drive_enabled', true);
        $tipeAuth = Setting::get('google_drive_auth_type', 'service_account');
        $emailOauth = Setting::get('google_drive_oauth_email');
        $namaOauth = Setting::get('google_drive_oauth_name');

        // Cek apakah OAuth sudah dikonfigurasi
        $oauthTerkonfigurasi = !empty(config('services.google_drive.oauth.client_id')) 
            && !empty(config('services.google_drive.oauth.client_secret'));

        // Ambil status koneksi dan info penyimpanan
        $statusKoneksi = null;
        $infoPenyimpanan = null;
        $infoFolder = null;

        $terhubung = ($tipeAuth === 'oauth' && $emailOauth) || 
                     ($tipeAuth === 'service_account' && $this->layananGoogleDrive->isConfigured());

        if ($terhubung) {
            try {
                // Tes koneksi
                $statusKoneksi = $this->layananGoogleDrive->testConnection($idFolder);
                
                // Ambil kuota penyimpanan
                $infoPenyimpanan = $this->layananGoogleDrive->getStorageQuota();
                
                // Ambil info folder
                if ($idFolder) {
                    $infoFolder = $this->layananGoogleDrive->getFolderInfo($idFolder);
                }
            } catch (\Exception $e) {
                $statusKoneksi = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return view('settings.google-drive', [
            'folderId' => $idFolder,
            'enabled' => $aktif,
            'authType' => $tipeAuth,
            'oauthEmail' => $emailOauth,
            'oauthName' => $namaOauth,
            'oauthConfigured' => $oauthTerkonfigurasi,
            'connectionStatus' => $statusKoneksi,
            'storageInfo' => $infoPenyimpanan,
            'folderInfo' => $infoFolder,
        ]);
    }

    /**
     * Perbarui pengaturan Google Drive
     */
    public function update(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|string|min:10|max:100',
            'enabled' => 'nullable|boolean',
        ], [
            'folder_id.required' => 'ID Folder wajib diisi',
            'folder_id.min' => 'ID Folder minimal 10 karakter',
        ]);

        $idFolder = trim($request->input('folder_id'));
        
        // Tes koneksi sebelum menyimpan
        if ($this->layananGoogleDrive->isConfigured()) {
            $hasilTes = $this->layananGoogleDrive->testConnection($idFolder);
            
            if (!$hasilTes['success']) {
                return back()->withErrors([
                    'folder_id' => 'Tidak dapat terhubung ke folder: ' . ($hasilTes['error'] ?? 'Kesalahan tidak diketahui')
                ])->withInput();
            }
        }

        // Simpan pengaturan
        Setting::set('google_drive_folder_id', $idFolder, 'string', 'google_drive', 'ID Folder atau Shared Drive');
        Setting::set('google_drive_enabled', $request->boolean('enabled') ? 'true' : 'false', 'boolean', 'google_drive', 'Aktifkan Google Drive');

        // Bersihkan cache
        Cache::forget('setting_google_drive_folder_id');
        Cache::forget('setting_google_drive_enabled');

        return redirect()->route('settings.google-drive.index')
            ->with('success', 'Pengaturan Google Drive berhasil disimpan!');
    }

    /**
     * Tes koneksi Google Drive (AJAX)
     */
    public function testConnection(Request $request)
    {
        $idFolder = $request->input('folder_id');

        if (!$this->layananGoogleDrive->isConfigured()) {
            return response()->json([
                'success' => false,
                'error' => 'Google Drive belum dikonfigurasi. Pastikan sudah terhubung dengan akun Google.',
            ]);
        }

        $hasil = $this->layananGoogleDrive->testConnection($idFolder);
        
        return response()->json($hasil);
    }

    /**
     * Ambil kuota penyimpanan (AJAX)
     */
    public function getStorageQuota()
    {
        if (!$this->layananGoogleDrive->isConfigured()) {
            return response()->json([
                'success' => false,
                'error' => 'Google Drive belum dikonfigurasi',
            ]);
        }

        $hasil = $this->layananGoogleDrive->getStorageQuota();
        
        return response()->json($hasil);
    }
}
