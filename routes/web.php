<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// EndPoint con protección de AUTH. Solo pueden acceder los usuarios.
Route::middleware('auth')->get('/api/documents', [DocumentController::class, 'apiIndex']);
Route::middleware('auth')->get('/api/document-statistics', [DocumentController::class, 'getDocumentStatistics']);
Route::middleware('auth')->get('/documents/download/{id}', [DocumentController::class, 'download'])->name('documents.download');
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
	Route::resource('documents', DocumentController::class);

	Route::patch('/documents/approve/{id}', [DocumentController::class, 'approve'])->name('documents.approve');
	Route::patch('/documents/reject/{id}', [DocumentController::class, 'reject'])->name('documents.reject');
	Route::middleware('auth')->delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // Modificar perfil del usuario utilizado
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // $_POST perfil del usuario utilizado
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Cerrar sesión

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';