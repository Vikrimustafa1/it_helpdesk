<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Tampilkan form register.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Proses register.
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'unit'                  => ['nullable', 'string', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:50'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'unit'     => $data['unit'] ?? null,
            'phone'    => $data['phone'] ?? null,
            'role'     => 'user', // role selalu 'user', tidak bisa dipilih dari form
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
