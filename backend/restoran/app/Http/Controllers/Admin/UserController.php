<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,kasir,chef,pelanggan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
            'status' => 'aktif' 
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,kasir,chef,pelanggan',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Cek apakah input password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        // Jika tidak diisi, jangan masukkan key 'password' ke array $data (biar password lama gak hilang)

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri yang sedang login!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
