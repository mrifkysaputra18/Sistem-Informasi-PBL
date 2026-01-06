<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    /**
     * Update the user's profile photo.
     */
    public function update(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'], // max 2MB
        ], [
            'photo.required' => 'Silakan pilih foto',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format foto harus JPG atau PNG',
            'photo.max' => 'Ukuran foto maksimal 2MB',
        ]);

        $user = $request->user();

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        // Update user profile_photo_path
        $user->update([
            'profile_photo_path' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Delete the user's profile photo.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->profile_photo_path) {
            // Delete photo file
            Storage::disk('public')->delete($user->profile_photo_path);

            // Remove from database
            $user->update([
                'profile_photo_path' => null,
            ]);

            return back()->with('success', 'Foto profil berhasil dihapus!');
        }

        return back()->with('error', 'Tidak ada foto profil untuk dihapus');
    }
}
