<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'adminProfileView'])->name('admin.profile.view');
    Route::post('/admin/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');
});


Route::get('/admin/login', [AdminController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login/submit', [AdminController::class, 'adminLoginSubmit'])->name('admin.loginSubmit');
Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');

Route::get('/admin/forget/password/', [AdminController::class, 'showAdminForgetPasswordForm'])->name('admin.forgetpassword');
Route::post('/admin/forgetpwd/submit', [AdminController::class, 'adminForgetPasswordSubmit'])->name('admin.forgetpwdsubmit');
Route::get('/admin/reset-password/{token}/{email}', [AdminController::class, 'showAdminResetPasswordForm']);
Route::post('/admin/resetpwd/submit', [AdminController::class, 'adminResetPasswordSubmit'])->name('admin.resetpwdsubmit');
