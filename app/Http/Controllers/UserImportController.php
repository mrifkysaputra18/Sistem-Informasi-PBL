<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
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
     * Process Excel import
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120' // Max 5MB
        ], [
            'file.required' => 'File Excel wajib diupload',
            'file.mimes' => 'File harus berformat .xlsx, .xls, atau .csv',
            'file.max' => 'Ukuran file maksimal 5MB'
        ]);

        try {
            $import = new UsersImport();
            
            // Import Excel file
            Excel::import($import, $request->file('file'));
            
            // Get statistics
            $importedCount = $import->getImportedCount();
            $skippedCount = $import->getSkippedCount();
            $errors = $import->getErrors();
            
            // Log import result
            Log::info('Excel import completed', [
                'imported' => $importedCount,
                'skipped' => $skippedCount,
                'total_errors' => count($errors)
            ]);
            
            // Prepare result message
            $message = "Import selesai! ";
            $message .= "✅ Berhasil: {$importedCount} mahasiswa";
            
            if ($skippedCount > 0) {
                $message .= " | ⚠️ Dilewati: {$skippedCount} baris";
            }
            
            // Store errors in session for display
            if (!empty($errors)) {
                session()->flash('import_errors', $errors);
            }
            
            if ($importedCount > 0) {
                return redirect()->route('admin.users.index')
                    ->with('success', $message);
            } else {
                return redirect()->back()
                    ->with('warning', 'Tidak ada data yang berhasil diimport. Periksa file Excel Anda.')
                    ->withInput();
            }
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            return redirect()->back()
                ->with('error', 'Validasi gagal! Periksa format data Excel.')
                ->with('import_errors', $errorMessages)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Import Excel failed', [
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
     */
    public function downloadTemplate()
    {
        $filePath = storage_path('app/templates/template-import-mahasiswa.xlsx');
        
        if (!file_exists($filePath)) {
            return redirect()->back()
                ->with('error', 'Template file tidak ditemukan. Silakan hubungi administrator.');
        }
        
        return response()->download($filePath, 'Template-Import-Mahasiswa.xlsx');
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
