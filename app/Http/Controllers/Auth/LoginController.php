<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'id_karyawan'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('remember');

        $attempt = [
            'email' => $credentials['id_karyawan'], // kolom email di DB menyimpan ID karyawan
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($attempt, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user !== null && $user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user !== null && in_array($user->role, ['teknisi_hardware', 'teknisi_software'])) {
                return redirect()->route('it.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'id_karyawan' => 'Kredensial tidak sesuai.',
        ])->onlyInput('id_karyawan');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
