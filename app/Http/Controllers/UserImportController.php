<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Imports\UsersMultiSheetImport;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class UserImportController extends Controller
{
    /**
     * Show import form
     */
    public function showImportForm()
    {
        $classes = ClassRoom::orderBy('name')->get();
        return view('users.import', compact('classes'));
    }

    /**
     * Process Excel import - Support MULTIPLE FILES
     */
    public function import(Request $request)
    {
        // Validate multiple files
        $request->validate([
            'files' => 'required|array|min:1|max:10', // Max 10 files
            'files.*' => 'required|mimes:xlsx,xls,csv|max:5120' // Each max 5MB
        ], [
            'files.required' => 'File Excel wajib diupload',
            'files.array' => 'Format upload tidak valid',
            'files.min' => 'Minimal 1 file harus diupload',
            'files.max' => 'Maksimal 10 file dapat diupload sekaligus',
            'files.*.mimes' => 'File harus berformat .xlsx, .xls, atau .csv',
            'files.*.max' => 'Ukuran setiap file maksimal 5MB'
        ]);

        try {
            $files = $request->file('files');
            $totalFiles = count($files);
            
            // Initialize counters
            $totalImported = 0;
            $totalSkipped = 0;
            $allErrors = [];
            $fileResults = [];
            
            Log::info('Starting multiple files import', [
                'total_files' => $totalFiles
            ]);
            
            // Process each file
            foreach ($files as $index => $file) {
                $fileNumber = $index + 1;
                $filename = $file->getClientOriginalName();
                
                Log::info("Processing file {$fileNumber}/{$totalFiles}", [
                    'filename' => $filename,
                    'size' => $file->getSize()
                ]);
                
                try {
                    // Use multi-sheet importer
                    $import = new UsersMultiSheetImport();
                    
                    // Import this file
                    Excel::import($import, $file);
                    
                    // Get statistics for this file
                    $importedCount = $import->getImportedCount();
                    $skippedCount = $import->getSkippedCount();
                    $errors = $import->getErrors();
                    
                    // Accumulate totals
                    $totalImported += $importedCount;
                    $totalSkipped += $skippedCount;
                    
                    // Store result for this file
                    $fileResults[] = [
                        'filename' => $filename,
                        'status' => $importedCount > 0 ? 'success' : 'warning',
                        'imported' => $importedCount,
                        'skipped' => $skippedCount,
                        'errors' => $errors
                    ];
                    
                    // Collect errors with file context
                    if (!empty($errors)) {
                        foreach ($errors as $error) {
                            $allErrors[] = "[{$filename}] {$error}";
                        }
                    }
                    
                    Log::info("File {$fileNumber} processed", [
                        'filename' => $filename,
                        'imported' => $importedCount,
                        'skipped' => $skippedCount
                    ]);
                    
                } catch (\Exception $fileError) {
                    // Error on specific file
                    $fileResults[] = [
                        'filename' => $filename,
                        'status' => 'error',
                        'imported' => 0,
                        'skipped' => 0,
                        'errors' => [$fileError->getMessage()]
                    ];
                    
                    $allErrors[] = "[{$filename}] Error: " . $fileError->getMessage();
                    
                    Log::error("File {$fileNumber} failed", [
                        'filename' => $filename,
                        'error' => $fileError->getMessage()
                    ]);
                }
            }
            
            // Log final result
            Log::info('Multiple files import completed', [
                'total_files' => $totalFiles,
                'total_imported' => $totalImported,
                'total_skipped' => $totalSkipped,
                'total_errors' => count($allErrors)
            ]);
            
            // Store file results in session
            session()->flash('file_results', $fileResults);
            
            // Store errors if any
            if (!empty($allErrors)) {
                session()->flash('import_errors', $allErrors);
            }
            
            // Prepare summary message
            $message = "Import {$totalFiles} file selesai! ";
            $message .= "✅ Total berhasil: {$totalImported} mahasiswa";
            
            if ($totalSkipped > 0) {
                $message .= " | ⚠️ Total dilewati: {$totalSkipped} baris";
            }
            
            if ($totalImported > 0) {
                return redirect()->route('admin.users.index')
                    ->with('success', $message);
            } else {
                return redirect()->back()
                    ->with('warning', 'Tidak ada data yang berhasil diimport dari semua file. Periksa file Excel Anda.')
                    ->withInput();
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Multiple files import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Import gagal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download Excel template
     * Support 2 methods: static file or dynamic generation
     */
    public function downloadTemplate()
    {
        // Method 1: Try static file first (if exists)
        $filePath = storage_path('app/templates/template-import-mahasiswa.xlsx');
        
        if (file_exists($filePath)) {
            return response()->download($filePath, 'Template-Import-Mahasiswa.xlsx');
        }
        
        // Method 2: Generate template dynamically
        try {
            return Excel::download(
                new \App\Exports\UserTemplateExport(), 
                'Template-Import-Mahasiswa.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal generate template: ' . $e->getMessage());
        }
    }

    /**
     * Show import preview (optional feature)
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $data = Excel::toArray(new UsersImport(), $request->file('file'));
            $rows = $data[0] ?? [];
            
            // Take first 10 rows for preview
            $preview = array_slice($rows, 0, 10);
            
            return view('users.import-preview', compact('preview'));
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membaca file: ' . $e->getMessage())
                ->withInput();
        }
    }
}
