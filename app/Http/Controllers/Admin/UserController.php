<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\RuangKelas;
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
        $query = Pengguna::with('classRoom');
        
        // Filter by role (Acting as Tab)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Filter by class (only if a specific class is selected)
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }
        
        // Filter by status (only if a specific status is selected)
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // Search by name, email, or nim
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        
        // Counts for Tabs Badges
        $counts = [
            'all' => Pengguna::count(),
            'admin' => Pengguna::where('role', 'admin')->count(),
            'koordinator' => Pengguna::where('role', 'koordinator')->count(),
            'dosen' => Pengguna::where('role', 'dosen')->count(),
            'mahasiswa' => Pengguna::where('role', 'mahasiswa')->count(),
        ];
        
        // Always paginate
        $users = $query->latest()->paginate(10)->withQueryString();
        $classRooms = RuangKelas::orderBy('name')->get();
        
        return view('admin.pengguna.daftar', compact('users', 'classRooms', 'counts'));
    }

    /**
     * Show students without groups
     */
    public function studentsWithoutGroup(Request $request)
    {
        $query = Pengguna::where('role', 'mahasiswa')
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
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }
        
        $students = $query->latest()->paginate(15);
        $classRooms = RuangKelas::orderBy('name')->get();
        
        // Get statistics per class
        $statsPerClass = RuangKelas::withCount([
            'students as total_students',
            'students as students_without_group' => function($q) {
                $q->whereDoesntHave('groupMembers');
            }
        ])->get();
        
        return view('admin.pengguna.tanpa-kelompok', compact('students', 'classRooms', 'statsPerClass'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classRooms = RuangKelas::orderBy('name')->get();
        return view('admin.pengguna.tambah', compact('classRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => [
                Rule::requiredIf($request->role === 'mahasiswa'),
                'nullable',
                'string',
                'unique:pengguna,nim'
            ],
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['mahasiswa', 'dosen', 'admin', 'koordinator'])],
            'program_studi' => 'nullable|string|max:255',
            'class_room_id' => 'nullable|exists:ruang_kelas,id',
            'is_active' => 'boolean',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        Pengguna::create($validated);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengguna $user)
    {
        $user->load(['classRoom', 'groups.classRoom', 'ledGroups']);
        return view('admin.pengguna.tampil', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengguna $user)
    {
        $classRooms = RuangKelas::orderBy('name')->get();
        return view('admin.pengguna.ubah', compact('user', 'classRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengguna $user)
    {
        $validated = $request->validate([
            'nim' => [
                Rule::requiredIf($request->role === 'mahasiswa'),
                'nullable',
                'string',
                Rule::unique('pengguna', 'nim')->ignore($user->id)
            ],
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('pengguna', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['mahasiswa', 'dosen', 'admin', 'koordinator'])],
            'program_studi' => 'nullable|string|max:255',
            'class_room_id' => 'nullable|exists:ruang_kelas,id',
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
    public function destroy(Pengguna $user)
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
    public function toggleActive(Pengguna $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);
        
        return back()->with('success', 'Status user berhasil diubah!');
    }
}
