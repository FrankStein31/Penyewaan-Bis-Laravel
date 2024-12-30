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
            'about' => 'nullable'
        ]);

        $user->update($request->all());

        return back()->with('success', 'Profile berhasil diperbarui');
    }
}
