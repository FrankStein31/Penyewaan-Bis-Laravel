<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')->get();
        return view('pages.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('pages.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $data = $request->except('avatar');
            $data['role'] = 'customer'; // Set role sebagai customer
            $data['password'] = Hash::make($request->password);
            $data['is_active'] = $request->has('is_active');

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '_' . $avatar->getClientOriginalName();
                $avatar->move(public_path('img/users'), $filename);
                $data['avatar'] = $filename;
            }

            User::create($data);

            return redirect()
                ->route('customers.index')
                ->with('success', 'Customer berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(User $customer)
    {
        if ($customer->role !== 'customer') {
            return redirect()->route('customers.index')->with('error', 'Data tidak ditemukan');
        }
        return view('pages.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            return redirect()->route('customers.index')->with('error', 'Data tidak ditemukan');
        }

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $customer->id,
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $data = $request->except(['avatar', 'password']);
            $data['is_active'] = $request->has('is_active');

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('avatar')) {
                if ($customer->avatar) {
                    $oldPath = public_path('img/users/' . $customer->avatar);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                $avatar = $request->file('avatar');
                $filename = time() . '_' . $avatar->getClientOriginalName();
                $avatar->move(public_path('img/users'), $filename);
                $data['avatar'] = $filename;
            }

            $customer->update($data);

            return redirect()
                ->route('customers.index')
                ->with('success', 'Data customer berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(User $customer)
    {
        if ($customer->role !== 'customer') {
            return redirect()->route('customers.index')->with('error', 'Data tidak ditemukan');
        }

        try {
            if ($customer->avatar) {
                $path = public_path('img/users/' . $customer->avatar);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            
            $customer->delete();

            return redirect()
                ->route('customers.index')
                ->with('success', 'Customer berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 