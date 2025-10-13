<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\ClassRoom;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Group::with(['classRoom', 'leader', 'members']);
        
        // Filter by classroom if provided
        if ($request->has('classroom') && $request->classroom) {
            $query->where('class_room_id', $request->classroom);
        }
        
        $groups = $query->latest()->paginate(10);
        $classRooms = ClassRoom::orderBy('name')->get();
        
        return view('groups.index', compact('groups', 'classRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $classRooms = ClassRoom::orderBy('name')->get();
        $projects = \App\Models\Project::orderBy('title')->get();
        $selectedClassroom = $request->input('classroom');
        return view('groups.create', compact('classRooms', 'projects', 'selectedClassroom'));
    }

    /**
     * Get available students for a specific classroom
     */
    public function getAvailableStudents(Request $request)
    {
        $classRoomId = $request->input('class_room_id');
        
        if (!$classRoomId) {
            return response()->json(['students' => []]);
        }
        
        // Get students who:
        // 1. Are mahasiswa
        // 2. Belong to the selected classroom (class_room_id)
        // 3. Are NOT already in any group in this classroom
        $students = User::where('role', 'mahasiswa')
            ->where('class_room_id', $classRoomId) // Filter by classroom
            ->whereDoesntHave('groupMembers', function($query) use ($classRoomId) {
                $query->whereHas('group', function($q) use ($classRoomId) {
                    $q->where('class_room_id', $classRoomId);
                });
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'politala_id']);
        
        return response()->json(['students' => $students]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        // Create group
        $group = Group::create($request->validated());
        
        // Add members if provided
        if ($request->has('members') && is_array($request->members)) {
            foreach ($request->members as $userId) {
                $group->members()->create([
                    'user_id' => $userId,
                    'role' => 'member'
                ]);
            }
        }
        
        // Set leader if provided
        if ($request->has('leader_id') && $request->leader_id) {
            $group->update(['leader_id' => $request->leader_id]);
        }
        
        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Kelompok berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $group->load(['classRoom', 'leader', 'members.user']);
        $availableStudents = User::where('role', 'mahasiswa')
            ->whereDoesntHave('groupMembers', function($q) use ($group) {
                $q->where('group_id', $group->id);
            })
            ->orderBy('name')
            ->get();
            
        return view('groups.show', compact('group', 'availableStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        $group->load(['classRoom', 'leader', 'members.user', 'weeklyTargets.completedByUser']);
        $classRooms = ClassRoom::orderBy('name')->get();
        
        // Get available students (not already in this group)
        $availableStudents = User::where('role', 'mahasiswa')
            ->whereDoesntHave('groupMembers', function($q) use ($group) {
                $q->where('group_id', $group->id);
            })
            ->orderBy('name')
            ->get();
            
        return view('groups.edit', compact('group', 'classRooms', 'availableStudents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update($request->validated());
        
        return redirect()
            ->route('groups.index')
            ->with('success', 'Kelompok berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()
            ->route('groups.index')
            ->with('success', 'Kelompok berhasil dihapus.');
    }
    
    /**
     * Add member to group
     */
    public function addMember(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'is_leader' => 'boolean'
        ]);
        
        // Cek apakah sudah mencapai maksimal 5 anggota
        if ($group->members()->count() >= 5) {
            return back()->with('error', 'Kelompok sudah mencapai maksimal 5 anggota!');
        }
        
        // Cek apakah user sudah menjadi anggota
        if ($group->members()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'Mahasiswa sudah menjadi anggota kelompok ini!');
        }
        
        // Jika ini akan jadi ketua, update ketua lama
        if ($request->is_leader) {
            $group->members()->update(['is_leader' => false]);
            $group->update(['leader_id' => $request->user_id]);
        }
        
        // Tambah anggota
        $group->members()->create([
            'user_id' => $request->user_id,
            'is_leader' => $request->is_leader ?? false,
            'status' => 'active'
        ]);
        
        return back()->with('success', 'Anggota berhasil ditambahkan!');
    }
    
    /**
     * Remove member from group
     */
    public function removeMember(Group $group, $memberId)
    {
        $member = $group->members()->findOrFail($memberId);
        
        // Jika yang dihapus adalah ketua, reset leader_id
        if ($member->is_leader) {
            $group->update(['leader_id' => null]);
        }
        
        $member->delete();
        
        return back()->with('success', 'Anggota berhasil dihapus dari kelompok!');
    }
    
    /**
     * Set a member as group leader
     */
    public function setLeader(Request $request, Group $group)
    {
        $request->validate([
            'member_id' => 'required|exists:group_members,id'
        ]);
        
        $member = $group->members()->findOrFail($request->member_id);
        
        // Remove leader status from all members
        $group->members()->update(['is_leader' => false]);
        
        // Set new leader
        $member->update(['is_leader' => true]);
        $group->update(['leader_id' => $member->user_id]);
        
        return back()->with('success', 'Ketua kelompok berhasil diubah!');
    }
}
