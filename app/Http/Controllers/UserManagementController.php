<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $users = User::query()
            ->with('department')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        return view('users.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'max:255', 'unique:users,email'],
            'role'          => ['required', Rule::in(['user', 'teknisi_hardware', 'teknisi_software'])],
            'unit'          => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'password'      => ['required', 'string', 'confirmed',
                                Password::min(8)->letters()->numbers()],
        ]);

        User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'role'          => $validated['role'],
            'unit'          => $validated['unit'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'phone'         => $validated['phone'] ?? null,
            'password'      => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $user = User::findOrFail($id);
        $departments = Department::orderBy('name')->get();
        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user        = User::findOrFail($id);
        $currentUser = auth()->user();
        $isAdmin     = $currentUser->role === 'admin';

        // Admin boleh ubah role, teknisi tidak boleh
        $roleRules = $isAdmin
            ? ['required', Rule::in(['user', 'teknisi_hardware', 'teknisi_software', 'admin'])]
            : [];

        $rules = [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'unit'          => ['nullable', 'string', 'max:255'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'password'      => ['nullable', 'string', 'confirmed',
                                Password::min(8)->letters()->numbers()],
        ];
        if ($isAdmin) {
            $rules['role'] = $roleRules;
        }

        $validated = $request->validate($rules);

        $user->name          = $validated['name'];
        $user->email         = $validated['email'];
        $user->unit          = $validated['unit'] ?? null;
        $user->department_id = $validated['department_id'] ?? null;
        $user->phone         = $validated['phone'] ?? null;

        // Hanya admin yang bisa mengubah role
        if ($isAdmin && isset($validated['role'])) {
            $user->role = $validated['role'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Arahkan ke route yang sesuai (admin atau it_support)
        $redirectRoute = $isAdmin ? 'admin.users.index' : 'users.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $user        = User::findOrFail($id);
        $currentUser = auth()->user();
        $isAdmin     = $currentUser->role === 'admin';

        // Tidak bisa hapus diri sendiri
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        // IT Support hanya bisa hapus user biasa, bukan sesama IT Support atau Admin
        if (!$isAdmin && $user->role !== 'user') {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus akun ini.');
        }

        // Admin tidak bisa hapus admin lain (opsional — proteksi ekstra)
        if ($isAdmin && $user->role === 'admin') {
            return back()->with('error', 'Akun Admin tidak dapat dihapus melalui sistem.');
        }

        $user->delete();

        $redirectRoute = $isAdmin ? 'admin.users.index' : 'users.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'User berhasil dihapus.');
    }
}
