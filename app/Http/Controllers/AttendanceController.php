<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isDosen()) {
            $projects = $user->projectsAsSuperviser()->get();
        } elseif ($user->isKoordinator()) {
            $projects = Project::where('program_studi', $user->program_studi)->get();
        } else {
            $projects = collect();
        }

        $selectedProject = null;
        $attendances = collect();

        if ($request->has('project_id') && $request->project_id) {
            $selectedProject = $projects->find($request->project_id);
            if ($selectedProject) {
                $attendances = Attendance::where('project_id', $selectedProject->id)
                    ->with(['user'])
                    ->when($request->date, function ($query) use ($request) {
                        $query->whereDate('date', $request->date);
                    })
                    ->orderBy('date', 'desc')
                    ->orderBy('user_id')
                    ->paginate(20);
            }
        }

        return view('attendances.index', compact('projects', 'selectedProject', 'attendances'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isDosen()) {
            $projects = $user->projectsAsSuperviser()->get();
        } elseif ($user->isKoordinator()) {
            $projects = Project::where('program_studi', $user->program_studi)->get();
        } else {
            abort(403);
        }

        $selectedProject = null;
        $students = collect();

        if ($request->has('project_id') && $request->project_id) {
            $selectedProject = $projects->find($request->project_id);
            if ($selectedProject) {
                $students = User::whereHas('groups.project', function ($query) use ($selectedProject) {
                    $query->where('id', $selectedProject->id);
                })->get();
            }
        }

        return view('attendances.create', compact('projects', 'selectedProject', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*' => 'required|in:present,absent,late,excused',
        ]);

        foreach ($validated['attendances'] as $userId => $status) {
            Attendance::updateOrCreate(
                [
                    'project_id' => $validated['project_id'],
                    'user_id' => $userId,
                    'date' => $validated['date'],
                ],
                [
                    'status' => $status,
                    'notes' => $request->input("notes.{$userId}"),
                ]
            );
        }

        return redirect()->route('attendances.index', ['project_id' => $validated['project_id']])
            ->with('success', 'Presensi berhasil disimpan.');
    }

    public function edit(Attendance $attendance)
    {
        $this->authorize('update', $attendance);
        
        return view('attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $this->authorize('update', $attendance);
        
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:255',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendances.index')
            ->with('success', 'Presensi berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance)
    {
        $this->authorize('delete', $attendance);
        
        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', 'Presensi berhasil dihapus.');
    }
}