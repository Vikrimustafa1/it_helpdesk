<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('users')->orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:departments,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:departments,name,' . $id],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $department = Department::withCount('users')->findOrFail($id);

        if ($department->users_count > 0) {
            return back()->with('error', 'Tidak dapat menghapus departemen yang masih memiliki user (' . $department->users_count . ' user).');
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}
