<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
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
    Route::get('/admin/profile/changepwd/', [AdminController::class, 'adminChangePwdForm'])->name('admin.changepwd.view');
    Route::post('/admin/profile/changepwd/submit', [AdminController::class, 'adminChangePwdSubmit'])->name('admin.changepwd.submit');
});

Route::get('/admin/login', [AdminController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login/submit', [AdminController::class, 'adminLoginSubmit'])->name('admin.loginSubmit');
Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');

Route::get('/admin/forget/password/', [AdminController::class, 'showAdminForgetPasswordForm'])->name('admin.forgetpassword');
Route::post('/admin/forgetpwd/submit', [AdminController::class, 'adminForgetPasswordSubmit'])->name('admin.forgetpwdsubmit');
Route::get('/admin/reset-password/{token}/{email}', [AdminController::class, 'showAdminResetPasswordForm']);
Route::post('/admin/resetpwd/submit', [AdminController::class, 'adminResetPasswordSubmit'])->name('admin.resetpwdsubmit');


//ALL ROUTE FOR CLIENT
Route::middleware(['client'])->group(function () {
    Route::get('/client/dashboard', [ClientController::class, 'clientDashboard'])->name('client.dashboard');
    Route::get('/client/profile', [ClientController::class, 'clientProfileView'])->name('client.profile.view');
    Route::post('/client/profile/update', [ClientController::class, 'clientProfileUpdate'])->name('client.profile.update');
    Route::get('/client/profile/changepwd/', [ClientController::class, 'clientChangePasswordForm'])->name('client.changepwd.view');
    Route::post('/client/profile/changepwd/submit', [ClientController::class, 'clientChangePasswordSubmit'])->name('client.changepwd.submit');
});
Route::get('/client/login', [ClientController::class, 'showClientLoginForm'])->name('client.login');
Route::get('/client/register', [ClientController::class, 'showClientRegisterForm'])->name('client.register');
Route::post('/client/register/submit', [ClientController::class, 'clientRegisterSubmit'])->name('client.register.submit');

Route::post('/client/login/submit', [ClientController::class, 'clientLoginSubmit'])->name('client.loginSubmit');
Route::get('/client/logout', [ClientController::class, 'clientLogout'])->name('client.logout');
