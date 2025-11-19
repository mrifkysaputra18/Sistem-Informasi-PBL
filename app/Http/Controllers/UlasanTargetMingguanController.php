<?php

namespace App\Http\Controllers;

use App\Models\TargetMingguan;
use App\Models\UlasanTargetMingguan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanTargetMingguanController extends Controller
{
    /**
     * Display a listing of targets that need review
     */
    public function index()
    {
        $user = Auth::user();
        
        // Base query
        $query = TargetMingguan::with(['group.classRoom', 'group.members', 'completedByUser'])
            ->whereIn('submission_status', ['submitted', 'late'])  // Hanya yang sudah submit
            ->where('is_reviewed', false);  // Belum direview
        
        // Dosen hanya bisa review target di kelas yang diampu
        if ($user->isDosen()) {
            $query->whereHas('group.classRoom', function($q) use ($user) {
                $q->where('dosen_id', $user->id);
            });
        }
        
        $pendingTargets = $query->orderBy('completed_at', 'desc')->paginate(20);

        \Log::info('Fetching pending targets for review', [
            'user_id' => $user->id,
            'role' => $user->role,
            'count' => $pendingTargets->total(),
        ]);

        return view('ulasan.target.daftar', compact('pendingTargets'));
    }

    /**
     * Show the form for reviewing a specific target
     */
    public function show(TargetMingguan $weeklyTarget)
    {
        $user = Auth::user();
        $target = $weeklyTarget->load(['group.members', 'group.classRoom', 'completedByUser', 'review']);
        
        // Dosen hanya bisa review target di kelas yang diampu
        if ($user->isDosen() && $target->group->classRoom->dosen_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mereview target ini. Target ini bukan dari kelas yang Anda ampu.');
        }
        
        // Check if already reviewed
        if ($target->isReviewed()) {
            return view('ulasan.target.tampil', compact('target'));
        }

        return view('ulasan.target.tambah', compact('target'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, TargetMingguan $weeklyTarget)
    {
        $user = Auth::user();
        $target = $weeklyTarget;
        
        // Dosen hanya bisa review target di kelas yang diampu
        if ($user->isDosen() && $target->group->classRoom->dosen_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mereview target ini. Target ini bukan dari kelas yang Anda ampu.');
        }
        
        // Check if already reviewed
        if ($target->isReviewed()) {
            return redirect()
                ->back()
                ->with('error', 'Target ini sudah dinilai sebelumnya!');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'required|string|max:1000',
            'suggestions' => 'nullable|string|max:1000',
            'status' => 'required|in:approved,needs_revision',
        ]);

        \Log::info('Creating WeeklyTarget Review', [
            'target_id' => $target->id,
            'reviewer_id' => Auth::id(),
            'score' => $validated['score'],
            'status' => $validated['status'],
        ]);

        // Create review
        $review = WeeklyTargetReview::create([
            'weekly_target_id' => $target->id,
            'reviewer_id' => Auth::id(),
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'suggestions' => $validated['suggestions'],
            'status' => $validated['status'],
        ]);

        // Update target as reviewed
        $target->update([
            'is_reviewed' => true,
            'reviewed_at' => now(),
            'reviewer_id' => Auth::id(),
        ]);

        \Log::info('WeeklyTarget Review Created', [
            'review_id' => $review->id,
            'target_id' => $target->id,
        ]);

        return redirect()
            ->route('target-reviews.index')
            ->with('success', 'Review berhasil disimpan! Target sudah dinilai.');
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(TargetMingguan $weeklyTarget)
    {
        $user = Auth::user();
        $target = $weeklyTarget->load(['group.members', 'group.classRoom', 'review']);
        
        // Dosen hanya bisa edit review target di kelas yang diampu
        if ($user->isDosen() && $target->group->classRoom->dosen_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit review ini. Target ini bukan dari kelas yang Anda ampu.');
        }
        
        if (!$target->isReviewed()) {
            return redirect()
                ->route('target-reviews.show', $target)
                ->with('error', 'Target ini belum dinilai!');
        }

        return view('ulasan.target.ubah', compact('target'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, TargetMingguan $weeklyTarget)
    {
        $user = Auth::user();
        $target = $weeklyTarget;
        $review = $target->review;
        
        // Dosen hanya bisa update review target di kelas yang diampu
        if ($user->isDosen() && $target->group->classRoom->dosen_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate review ini. Target ini bukan dari kelas yang Anda ampu.');
        }

        if (!$review) {
            return redirect()
                ->back()
                ->with('error', 'Review tidak ditemukan!');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'required|string|max:1000',
            'suggestions' => 'nullable|string|max:1000',
            'status' => 'required|in:approved,needs_revision',
        ]);

        \Log::info('Updating WeeklyTarget Review', [
            'review_id' => $review->id,
            'target_id' => $target->id,
            'reviewer_id' => Auth::id(),
        ]);

        $review->update($validated);

        return redirect()
            ->route('target-reviews.show', $target)
            ->with('success', 'Review berhasil diperbarui!');
    }

    /**
     * Download single evidence file from target submission
     */
    public function downloadFile(TargetMingguan $weeklyTarget, $fileIndex)
    {
        $target = $weeklyTarget;
        
        // Check if user has permission (dosen/koordinator/admin)
        if (!in_array(Auth::user()->role, ['dosen', 'koordinator', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        // Get evidence files
        $evidenceFiles = $target->evidence_files ?? [];
        
        if (!isset($evidenceFiles[$fileIndex])) {
            abort(404, 'File not found');
        }

        $file = $evidenceFiles[$fileIndex];

        \Log::info('Dosen downloading file', [
            'target_id' => $target->id,
            'file_index' => $fileIndex,
            'user_id' => Auth::id(),
            'file' => $file,
        ]);

        // Check if file is from Google Drive
        if (isset($file['file_id']) && isset($file['file_url'])) {
            // Redirect to Google Drive view/download URL
            $downloadUrl = "https://drive.google.com/uc?export=download&id=" . $file['file_id'];
            return redirect($downloadUrl);
        }

        // Check if file is from local storage
        if (isset($file['local_path'])) {
            $filePath = storage_path('app/public/' . $file['local_path']);
            
            if (!file_exists($filePath)) {
                \Log::error('File not found in local storage', [
                    'path' => $filePath,
                    'file' => $file,
                ]);
                abort(404, 'File not found in storage');
            }

            $fileName = $file['file_name'] ?? basename($filePath);
            
            return response()->download($filePath, $fileName);
        }

        abort(404, 'Invalid file data');
    }

    /**
     * Download all evidence files as ZIP
     */
    public function downloadAllFiles(TargetMingguan $weeklyTarget)
    {
        $target = $weeklyTarget;
        
        // Check if user has permission
        if (!in_array(Auth::user()->role, ['dosen', 'koordinator', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        $evidenceFiles = $target->evidence_files ?? [];
        
        if (empty($evidenceFiles)) {
            return redirect()->back()->with('error', 'Tidak ada file untuk didownload');
        }

        \Log::info('Dosen downloading all files', [
            'target_id' => $target->id,
            'user_id' => Auth::id(),
            'file_count' => count($evidenceFiles),
        ]);

        // Create ZIP file
        $zipFileName = 'Target_Week' . $target->week_number . '_' . $target->group->name . '_' . now()->format('YmdHis') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            \Log::error('Failed to create ZIP file', ['path' => $zipPath]);
            return redirect()->back()->with('error', 'Gagal membuat file ZIP');
        }

        $filesAdded = 0;

        foreach ($evidenceFiles as $index => $file) {
            try {
                if (isset($file['local_path'])) {
                    // Local storage file
                    $filePath = storage_path('app/public/' . $file['local_path']);
                    
                    if (file_exists($filePath)) {
                        $fileName = $file['file_name'] ?? 'file_' . ($index + 1) . '.dat';
                        $zip->addFile($filePath, $fileName);
                        $filesAdded++;
                    }
                } elseif (isset($file['file_id'])) {
                    // Google Drive file - Add a text file with link
                    $linkContent = "File ini tersimpan di Google Drive.\n\n";
                    $linkContent .= "Nama: " . ($file['file_name'] ?? 'Unknown') . "\n";
                    $linkContent .= "Link: " . ($file['file_url'] ?? 'N/A') . "\n";
                    $linkContent .= "Download: https://drive.google.com/uc?export=download&id=" . $file['file_id'] . "\n";
                    
                    $zip->addFromString(($file['file_name'] ?? 'file_' . ($index + 1)) . '.txt', $linkContent);
                    $filesAdded++;
                }
            } catch (\Exception $e) {
                \Log::error('Error adding file to ZIP', [
                    'file' => $file,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Add target info as text file
        $infoContent = "=== INFORMASI TARGET MINGGUAN ===\n\n";
        $infoContent .= "Kelompok: " . $target->group->name . "\n";
        $infoContent .= "Kelas: " . ($target->group->classRoom->name ?? '-') . "\n";
        $infoContent .= "Minggu: " . $target->week_number . "\n";
        $infoContent .= "Target: " . $target->title . "\n";
        $infoContent .= "Deskripsi: " . ($target->description ?? '-') . "\n";
        $infoContent .= "Diselesaikan oleh: " . ($target->completedByUser->name ?? '-') . "\n";
        $infoContent .= "Tanggal Submit: " . ($target->completed_at ? $target->completed_at->format('d/m/Y H:i') : '-') . "\n";
        $infoContent .= "Catatan: " . ($target->submission_notes ?? '-') . "\n";
        $infoContent .= "\nTotal file: " . $filesAdded . "\n";
        $infoContent .= "Didownload oleh: " . Auth::user()->name . "\n";
        $infoContent .= "Tanggal download: " . now()->format('d/m/Y H:i') . "\n";

        $zip->addFromString('_INFO_TARGET.txt', $infoContent);

        $zip->close();

        if ($filesAdded === 0) {
            @unlink($zipPath);
            return redirect()->back()->with('error', 'Tidak ada file yang bisa didownload');
        }

        \Log::info('ZIP file created successfully', [
            'files_added' => $filesAdded,
            'zip_path' => $zipPath,
        ]);

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}
