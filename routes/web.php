<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\manageUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Gate;

Route::get('/init-admin-setup-super-secret', function () {
    // 1. Buat role admin & staff jika belum ada di database
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'staff']);

    // 2. Cari akun Anda berdasarkan email (sesuaikan email Anda)
    $user = User::where('email', 'aaa189943@gmail.com')->first();

    if ($user) {
        // 3. Pasangkan role admin ke akun Anda
        $user->assignRole($adminRole);
        return 'Mantap! Akun Anda berhasil diubah menjadi ADMIN.';
    }

    return 'User tidak ditemukan, cek kembali email Anda.';
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/manageUser', [UserController::class, 'index'])->name('manageUser'); // Keep alias if needed
});

Route::middleware(['auth', 'role:admin|leader|staff'])->group(function () {
    Route::get('/projects/history', [\App\Http\Controllers\ProjectController::class, 'history'])->name('projects.history');
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::post('/projects/{project}/add-member', [\App\Http\Controllers\ProjectController::class, 'addMember'])->name('projects.addMember');
    Route::delete('/projects/{project}/remove-member/{user}', [\App\Http\Controllers\ProjectController::class, 'removeMember'])->name('projects.removeMember');
    
    // Task Management (Nested)
    Route::resource('projects.tasks', \App\Http\Controllers\TaskController::class);
    Route::post('/projects/{project}/tasks/{task}/approve', [\App\Http\Controllers\TaskController::class, 'approve'])->name('projects.tasks.approve');
    Route::post('/projects/{project}/tasks/{task}/reject', [\App\Http\Controllers\TaskController::class, 'reject'])->name('projects.tasks.reject');
    
    // Comments
    Route::post('/tasks/{task}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
