<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Facades\Excel; // Temporary disabled - using CSV workaround
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
            $rows = Excel::toArray([], $request->file('file'))[0];
            
            // Skip header row
            array_shift($rows);
            
            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                DB::beginTransaction();
                
                try {
                    // Validate row structure
                    if (count($row) < 2) {
                        throw new \Exception('Format baris tidak lengkap');
                    }

                    $namaKelompok = $row[0];
                    $ketuaEmail = $row[1];
                    
                    if (empty($namaKelompok) || empty($ketuaEmail)) {
                        continue; // Skip empty rows
                    }

                    // Create group
                    $group = Group::create([
                        'name' => $namaKelompok,
                        'class_room_id' => $classRoomId,
                        'max_members' => 5,
                    ]);

                    // Add leader
                    $leader = User::where('email', $ketuaEmail)->first();
                    if ($leader) {
                        $group->members()->create([
                            'user_id' => $leader->id,
                            'is_leader' => true,
                            'status' => 'active',
                        ]);
                        $group->update(['leader_id' => $leader->id]);
                    } else {
                        throw new \Exception("Email ketua tidak ditemukan: {$ketuaEmail}");
                    }

                    // Add members (columns 2-5)
                    for ($i = 2; $i <= 5; $i++) {
                        if (isset($row[$i]) && !empty($row[$i])) {
                            $member = User::where('email', $row[$i])->first();
                            if ($member) {
                                if (!$group->members()->where('user_id', $member->id)->exists()) {
                                    $group->members()->create([
                                        'user_id' => $member->id,
                                        'is_leader' => false,
                                        'status' => 'active',
                                    ]);
                                }
                            }
                        }
                    }

                    DB::commit();
                    $imported++;
                    
                } catch (\Exception $e) {
                    DB::rollback();
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if (count($errors) > 0) {
                return redirect()
                    ->back()
                    ->with('warning', "Import selesai. Berhasil: {$imported}, Gagal: " . count($errors) . ". Error: " . implode(', ', array_slice($errors, 0, 3)));
            }

            return redirect()
                ->route('classrooms.show', $classRoomId)
                ->with('success', "Berhasil import {$imported} kelompok!");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel untuk import kelompok
     */
    public function downloadGroupTemplate()
    {
        // Temporary workaround: Generate CSV instead of Excel
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Template_Import_Kelompok.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['nama_kelompok', 'ketua_email', 'anggota_1_email', 'anggota_2_email', 'anggota_3_email', 'anggota_4_email']);
            
            // Example data
            fputcsv($file, ['Kelompok 1', 'mahasiswa1@politala.ac.id', 'mahasiswa2@politala.ac.id', 'mahasiswa3@politala.ac.id', 'mahasiswa4@politala.ac.id', 'mahasiswa5@politala.ac.id']);
            fputcsv($file, ['Kelompok 2', 'mahasiswa6@politala.ac.id', 'mahasiswa7@politala.ac.id', 'mahasiswa8@politala.ac.id', 'mahasiswa9@politala.ac.id', 'mahasiswa10@politala.ac.id']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        
        // Original Excel download (uncomment when composer issue is fixed):
        // return Excel::download(new \App\Exports\GroupTemplateExport, 'Template_Import_Kelompok.xlsx');
    }
}

