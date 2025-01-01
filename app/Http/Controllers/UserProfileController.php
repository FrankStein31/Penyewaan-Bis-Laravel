<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'firstname' => 'required',
            'lastname' => 'nullable',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'address' => 'nullable',
            'city' => 'nullable',
            'country' => 'nullable',
            'postal' => 'nullable',
            'about' => 'nullable',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except('avatar');

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
    }
}
