<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('pages.user-profile');
    }

    public function edit()
    {
        return view('pages.user-profile');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => 'required|unique:users,username,'.$user->id,
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'country' => 'nullable',
            'postal' => 'nullable',
            'about' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:8|confirmed',
            'current_password' => 'required_with:password'
        ]);

        try {
            $data = $request->except(['avatar', 'password', 'password_confirmation', 'current_password']);

            // Cek password saat ini jika user ingin mengubah password
            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
                }
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                // Hapus foto lama jika ada
                if ($user->avatar) {
                    $oldPath = public_path('img/users/' . $user->avatar);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                $avatar = $request->file('avatar');
                $filename = time() . '_' . $avatar->getClientOriginalName();
                $avatar->move(public_path('img/users'), $filename);
                $data['avatar'] = $filename;
            }

            $user->update($data);

            return back()->with('success', 'Profile berhasil diperbarui');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
