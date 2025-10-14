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
     * Import groups from Excel
     */
    public function importGroups(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $classRoomId = $request->class_room_id;
            $classRoom = ClassRoom::findOrFail($classRoomId);
            
            // Import using GroupsImport class
            $import = new GroupsImport($classRoomId);
            Excel::import($import, $request->file('file'));
            
            $imported = $import->getImportedCount();
            $skipped = $import->getSkippedCount();
            $errors = $import->getErrors();

            \Log::info('Group import completed', [
                'class_room_id' => $classRoomId,
                'imported' => $imported,
                'skipped' => $skipped,
                'errors_count' => count($errors)
            ]);

            // Build response message
            if ($imported > 0 && count($errors) === 0) {
                return redirect()
                    ->route('groups.index')
                    ->with('success', "✅ Berhasil import {$imported} kelompok ke kelas {$classRoom->name}!");
            }

            if ($imported > 0 && count($errors) > 0) {
                $errorMessages = implode(' | ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $errorMessages .= " ... dan " . (count($errors) - 5) . " error lainnya";
                }
                
                return redirect()
                    ->route('groups.index')
                    ->with('warning', "⚠️ Import selesai sebagian. Berhasil: {$imported}, Gagal: {$skipped}. Error: {$errorMessages}");
            }

            if ($imported === 0) {
                $errorMessages = implode(' | ', array_slice($errors, 0, 3));
                return redirect()
                    ->back()
                    ->with('error', "❌ Import gagal. Tidak ada kelompok yang berhasil diimport. Error: {$errorMessages}");
            }

            return redirect()
                ->back()
                ->with('error', '❌ Import gagal. Silakan periksa format file Excel Anda.');
                
        } catch (\Exception $e) {
            \Log::error('Group import exception', [
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

