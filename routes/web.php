<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\AdminController;


// Landing page untuk guest
Route::get('/', function () {
    if (!auth()->check()) {
        return view('welcome');
    }

    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if (in_array($user->role, ['teknisi_hardware', 'teknisi_software'], true)) {
        return redirect()->route('it.dashboard');
    }

    return redirect()->route('dashboard');
})->name('home');

// Auth routes (guest)
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Notifikasi (semua user yang sudah login)
Route::middleware('auth')->group(function (): void {
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifikasi/poll', [NotificationController::class, 'poll'])->name('notifications.poll');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::get('/notifikasi/{id}/baca', [NotificationController::class, 'read'])->name('notifications.read');
});

// User (pelapor)
Route::middleware(['auth', 'role:user'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');

    Route::get('/tiket/buat', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tiket/buat', [TicketController::class, 'store'])->name('tickets.store');

    Route::get('/tiket/riwayat', [TicketController::class, 'myTickets'])->name('tickets.my');

    // Feedback tiket (hanya pelapor, setelah tiket selesai)
    Route::post('/tiket/{id}/feedback', [FeedbackController::class, 'store'])->name('tickets.feedback');

    // Catatan progress dari user (saat status Diproses)
    Route::post('/tiket/{id}/note', [TicketController::class, 'addUserNote'])->name('tickets.user-note');
});

// Detail tiket (semua role login)
Route::middleware('auth')->get('/tiket/{id}', [TicketController::class, 'show'])->name('tickets.show');

// Teknisi Hardware + Teknisi Software + Admin
Route::middleware(['auth', 'role:teknisi_hardware|teknisi_software|admin'])->prefix('it')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('it.dashboard');

    Route::get('/tiket', [TicketController::class, 'index'])->name('it.tickets.index');
    Route::get('/tiket/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tiket/{id}', [TicketController::class, 'update'])->name('tickets.update');
    Route::post('/tiket/{id}/progress', [TicketController::class, 'addProgress'])->name('tickets.progress');

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/laporan/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::post('/laporan/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');

    // Manajemen User (semua teknisi & admin)
    Route::middleware('role:teknisi_hardware|teknisi_software|admin')->group(function (): void {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        // Manajemen Departemen
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        // Manajemen Kategori Tiket
        Route::get('/ticket-categories', [TicketCategoryController::class, 'index'])->name('ticket-categories.index');
        Route::get('/ticket-categories/create', [TicketCategoryController::class, 'create'])->name('ticket-categories.create');
        Route::post('/ticket-categories', [TicketCategoryController::class, 'store'])->name('ticket-categories.store');
        Route::get('/ticket-categories/{id}/edit', [TicketCategoryController::class, 'edit'])->name('ticket-categories.edit');
        Route::put('/ticket-categories/{id}', [TicketCategoryController::class, 'update'])->name('ticket-categories.update');
        Route::delete('/ticket-categories/{id}', [TicketCategoryController::class, 'destroy'])->name('ticket-categories.destroy');
    });
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manajemen User (admin punya akses penuh termasuk ubah role & hapus IT Support)
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Manajemen Departemen
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // Manajemen Kategori Tiket
    Route::get('/ticket-categories', [TicketCategoryController::class, 'index'])->name('ticket-categories.index');
    Route::get('/ticket-categories/create', [TicketCategoryController::class, 'create'])->name('ticket-categories.create');
    Route::post('/ticket-categories', [TicketCategoryController::class, 'store'])->name('ticket-categories.store');
    Route::get('/ticket-categories/{id}/edit', [TicketCategoryController::class, 'edit'])->name('ticket-categories.edit');
    Route::put('/ticket-categories/{id}', [TicketCategoryController::class, 'update'])->name('ticket-categories.update');
    Route::delete('/ticket-categories/{id}', [TicketCategoryController::class, 'destroy'])->name('ticket-categories.destroy');

    // Laporan
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/laporan/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::post('/laporan/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
});

