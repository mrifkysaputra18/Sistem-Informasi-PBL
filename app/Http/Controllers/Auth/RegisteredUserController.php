<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.daftar');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:mahasiswa,dosen'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Pengguna::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Add NIM validation only for mahasiswa
        if ($request->role === 'mahasiswa') {
            $validationRules['nim'] = ['required', 'string', 'max:20', 'unique:'.Pengguna::class];
        }

        $request->validate($validationRules);

        $userData = [
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        // Add NIM only for mahasiswa
        if ($request->role === 'mahasiswa') {
            $userData['nim'] = $request->nim;
        }

        $user = Pengguna::create($userData);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
