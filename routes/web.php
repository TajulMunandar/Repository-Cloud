<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.main'); // pastikan file main.blade.php ada di resources/views/pages/
})->name('main');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index']);
    Route::resource('users', UserController::class);
    Route::get('users-data', [UserController::class, 'getData'])->name('users.data');
    Route::get('files/view/{id}', [FileController::class, 'view'])->name('files.view');
    Route::post('/files/update-stats', [FileController::class, 'updateUploadStats'])->name('files.updateUploadStats');
    Route::get('files/download/{id}', [FileController::class, 'download'])->name('files.download');
    Route::get('/files/trash', [FileController::class, 'trash'])->name('files.trash');
    Route::post('/files/restore/{id}', [FileController::class, 'restore'])->name('files.restore');
    Route::delete('/files/force-delete/{id}', [FileController::class, 'forceDelete'])->name('files.forceDelete');
    Route::resource('files', FileController::class)->except(['edit', 'update']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
