<?php

namespace App\Http\Controllers;

use App\Models\{TargetMingguan, Kelompok};
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

/**
 * Controller untuk MAHASISWA upload kemajuan mingguan
 * Ini adalah alternatif lebih fleksibel dari PengumpulanTargetMingguanController
 */
class KemajuanMingguanController extends Controller
{
    protected GoogleDriveService $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    /**
     * Form upload kemajuan mingguan
     */
    public function upload()
    {
        $user = auth()->user();
        
        // Cari kelompok mahasiswa
        $group = $user->kelompok()->with(['classRoom', 'targets' => function($query) {
            $query->where('is_active', true)
                  ->where(function($q) {
                      $q->whereNull('deadline')
                        ->orWhere('deadline', '>=', now());
                  })
                  ->orderBy('minggu_ke');
        }])->first();

        if (!$group) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda belum tergabung dalam kelompok manapun.');
        }

        $activeTargets = $group->targets ?? collect();

        return view('kemajuan-mingguan.upload', compact('group', 'activeTargets'));
    }

    /**
     * Store progress upload
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_id' => 'required|exists:target_mingguan,id',
            'files.*' => 'nullable|file|max:10240', // Max 10MB per file
            'catatan' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $target = TargetMingguan::findOrFail($validated['target_id']);

        // Verifikasi bahwa target ini untuk kelompok mahasiswa
        $group = $user->kelompok;
        if (!$group || $group->id !== $target->kelompok_id) {
            return back()->with('error', 'Anda tidak berhak mengakses target ini.');
        }

        // Verifikasi target masih aktif
        if (!$target->is_active) {
            return back()->with('error', 'Target ini sudah tidak aktif.');
        }

        // Verifikasi deadline belum lewat
        if ($target->deadline && $target->deadline < now()) {
            return back()->with('error', 'Deadline target ini sudah lewat.');
        }

        // Upload files ke Google Drive jika ada
        $uploadedFiles = $target->files ?? [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                try {
                    $path = $file->store('submissions/' . $group->id . '/week-' . $target->minggu_ke, 'public');
                    $uploadedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'uploaded_at' => now()->toDateTimeString(),
                        'uploaded_by' => $user->id,
                    ];
                } catch (\Exception $e) {
                    return back()->with('error', 'Gagal mengupload file: ' . $e->getMessage());
                }
            }
        }

        // Update target dengan files dan catatan
        $target->update([
            'files' => $uploadedFiles,
            'catatan_mahasiswa' => $validated['catatan'] ?? $target->catatan_mahasiswa,
            'status' => 'submitted',
            'submitted_at' => now(),
            'submitted_by' => $user->id,
        ]);

        return redirect()->route('targets.submissions.index')
            ->with('ok', 'Kemajuan mingguan berhasil diupload!');
    }

    /**
     * Cancel/hapus progress yang sudah diupload
     */
    public function cancel(TargetMingguan $weeklyProgress)
    {
        $user = auth()->user();
        $target = $weeklyProgress;

        // Verifikasi ownership
        $group = $user->kelompok;
        if (!$group || $group->id !== $target->kelompok_id) {
            return back()->with('error', 'Anda tidak berhak mengakses target ini.');
        }

        // Verifikasi belum direview
        if ($target->review()->exists()) {
            return back()->with('error', 'Progress yang sudah direview tidak dapat dibatalkan.');
        }

        // Hapus files dari storage
        if ($target->files) {
            foreach ($target->files as $file) {
                if (isset($file['path'])) {
                    \Storage::disk('public')->delete($file['path']);
                }
            }
        }

        // Reset target ke draft
        $target->update([
            'files' => null,
            'status' => 'draft',
            'submitted_at' => null,
            'submitted_by' => null,
        ]);

        return back()->with('ok', 'Progress berhasil dibatalkan.');
    }
}
