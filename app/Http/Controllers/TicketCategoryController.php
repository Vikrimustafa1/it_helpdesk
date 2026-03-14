<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketCategoryController extends Controller
{
    public function index(): View
    {
        $categories = TicketCategory::withCount('tickets')->orderBy('name')->get();
        return view('ticket-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('ticket-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100', 'unique:ticket_categories,name'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon'  => ['nullable', 'string', 'max:100'],
        ]);

        TicketCategory::create($validated);

        return redirect()->route('ticket-categories.index')
            ->with('success', 'Kategori tiket berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $category = TicketCategory::findOrFail($id);
        return view('ticket-categories.edit', compact('category'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $category = TicketCategory::findOrFail($id);

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100', 'unique:ticket_categories,name,' . $id],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon'  => ['nullable', 'string', 'max:100'],
        ]);

        $category->update($validated);

        return redirect()->route('ticket-categories.index')
            ->with('success', 'Kategori tiket berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = TicketCategory::withCount('tickets')->findOrFail($id);

        if ($category->tickets_count > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang masih digunakan (' . $category->tickets_count . ' tiket).');
        }

        $category->delete();

        return redirect()->route('ticket-categories.index')
            ->with('success', 'Kategori tiket berhasil dihapus.');
    }
}
