<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba Login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Cek Role & Redirect ke halaman yang sesuai
            $role = Auth::user()->role;

            if ($role == 'admin') {
                return redirect()->route('admin.index');
            } elseif ($role == 'kasir') {
                return redirect()->route('kasir.index');
            } elseif ($role == 'chef') {
                return redirect()->route('dapur.index');
            } else {
                // Fallback jika role tidak dikenali
                return redirect('/');
            }
        }

        // Jika Gagal Login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
