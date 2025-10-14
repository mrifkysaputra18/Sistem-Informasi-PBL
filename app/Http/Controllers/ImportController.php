<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
use App\Imports\GroupsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    /**
     * Show import groups form
     */
    public function showGroupsImport()
    {
        $classRooms = ClassRoom::with('subject')->orderBy('name')->get();
        return view('imports.groups', compact('classRooms'));
    }

    /**
     * Import groups from Excel - Support MULTIPLE FILES
     */
    public function importGroups(Request $request)
    {
        // Validate multiple files
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'class_room_id.required' => 'Kelas wajib dipilih',
            'files.required' => 'File Excel wajib diupload',
            'files.array' => 'Format upload tidak valid',
            'files.min' => 'Minimal 1 file harus diupload',
            'files.max' => 'Maksimal 10 file dapat diupload sekaligus',
            'files.*.mimes' => 'File harus berformat .xlsx, .xls, atau .csv',
            'files.*.max' => 'Ukuran setiap file maksimal 5MB'
        ]);

        try {
            $classRoomId = $request->class_room_id;
            $classRoom = ClassRoom::findOrFail($classRoomId);
            $files = $request->file('files');
            $totalFiles = count($files);
            
            // Initialize counters
            $totalImported = 0;
            $totalSkipped = 0;
            $allErrors = [];
            $fileResults = [];
            
            \Log::info('Starting multiple files group import', [
                'class_room_id' => $classRoomId,
                'class_name' => $classRoom->name,
                'total_files' => $totalFiles
            ]);
            
            // Process each file
            foreach ($files as $index => $file) {
                $fileNumber = $index + 1;
                $filename = $file->getClientOriginalName();
                
                \Log::info("Processing file {$fileNumber}/{$totalFiles}", [
                    'filename' => $filename,
                    'size' => $file->getSize()
                ]);
                
                try {
                    // Import using GroupsImport class
                    $import = new GroupsImport($classRoomId);
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
                    
                    \Log::info("File {$fileNumber} processed", [
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
                    
                    \Log::error("File {$fileNumber} failed", [
                        'filename' => $filename,
                        'error' => $fileError->getMessage()
                    ]);
                }
            }
            
            // Log final result
            \Log::info('Multiple files group import completed', [
                'class_room_id' => $classRoomId,
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
            $message .= "✅ Total berhasil: {$totalImported} kelompok";
            
            if ($totalSkipped > 0) {
                $message .= " | ⚠️ Total dilewati: {$totalSkipped} baris";
            }
            
            if ($totalImported > 0) {
                return redirect()
                    ->route('groups.index')
                    ->with('success', $message);
            } else {
                return redirect()
                    ->back()
                    ->with('warning', 'Tidak ada kelompok yang berhasil diimport dari semua file. Periksa file Excel Anda.')
                    ->withInput();
            }
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('Multiple files group import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel untuk import kelompok
     */
    public function downloadGroupTemplate()
    {
        return Excel::download(new \App\Exports\GroupTemplateExport, 'Template_Import_Kelompok.xlsx');
    }
}

