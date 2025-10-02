<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
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
        return Excel::download(new \App\Exports\GroupTemplateExport, 'Template_Import_Kelompok.xlsx');
    }
}

