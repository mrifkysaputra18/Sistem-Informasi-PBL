<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users with filters
     */
    public function index(Request $request)
    {
        $query = User::with('classRoom');
        
        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }
        
        // Filter by class
        if ($request->has('class_room_id') && $request->class_room_id !== '') {
            $query->where('class_room_id', $request->class_room_id);
        }
        
        // Filter by status
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }
        
        // Search by name or email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('politala_id', 'like', "%{$search}%");
            });
        }
        
        // Group by role untuk tampilan
        $usersByRole = [
            'admin' => User::where('role', 'admin')->with('classRoom')->latest()->get(),
            'koordinator' => User::where('role', 'koordinator')->with('classRoom')->latest()->get(),
            'dosen' => User::where('role', 'dosen')->with('classRoom')->latest()->get(),
            'mahasiswa' => User::where('role', 'mahasiswa')->with('classRoom')->latest()->get(),
        ];
        
        // Apply filters ke grouped data jika ada filter
        if ($request->hasAny(['role', 'class_room_id', 'is_active', 'search'])) {
            $users = $query->latest()->paginate(15);
            $classRooms = ClassRoom::orderBy('name')->get();
            return view('admin.users.index', compact('users', 'classRooms', 'usersByRole'));
        }
        
        $users = $query->latest()->paginate(15);
        $classRooms = ClassRoom::orderBy('name')->get();
        
        return view('admin.users.index', compact('users', 'classRooms', 'usersByRole'));
    }

    /**
     * Show students without groups
     */
    public function studentsWithoutGroup(Request $request)
    {
        $query = User::where('role', 'mahasiswa')
            ->with('classRoom')
            ->whereDoesntHave('groupMembers');
        
        // Filter by class
        if ($request->has('class_room_id') && $request->class_room_id !== '') {
            $query->where('class_room_id', $request->class_room_id);
        }
        
        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('politala_id', 'like', "%{$search}%");
            });
        }
        
        $students = $query->latest()->paginate(15);
        $classRooms = ClassRoom::orderBy('name')->get();
        
        // Get statistics per class
        $statsPerClass = ClassRoom::withCount([
            'students as total_students',
            'students as students_without_group' => function($q) {
                $q->whereDoesntHave('groupMembers');
            }
        ])->get();
        
        return view('admin.users.without-group', compact('students', 'classRooms', 'statsPerClass'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classRooms = ClassRoom::orderBy('name')->get();
        return view('admin.users.create', compact('classRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'politala_id' => 'required|string|unique:users,politala_id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['mahasiswa', 'dosen', 'admin', 'koordinator'])],
            'phone' => 'nullable|string|max:20',
            'program_studi' => 'nullable|string|max:255',
            'class_room_id' => 'nullable|exists:class_rooms,id',
            'is_active' => 'boolean',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['classRoom', 'groups.classRoom', 'ledGroups']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $classRooms = ClassRoom::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'classRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'politala_id' => ['required', 'string', Rule::unique('users', 'politala_id')->ignore($user->id)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['mahasiswa', 'dosen', 'admin', 'koordinator'])],
            'phone' => 'nullable|string|max:20',
            'program_studi' => 'nullable|string|max:255',
            'class_room_id' => 'nullable|exists:class_rooms,id',
            'is_active' => 'boolean',
        ]);
        
        // Only update password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
        }
        
        $user->delete();
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Toggle user active status
     */
    public function toggleActive(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);
        
        return back()->with('success', 'Status user berhasil diubah!');
    }
}
