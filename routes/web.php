<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

// EndPoint con protección de AUTH. Solo pueden acceder los usuarios.
Route::middleware('auth')->get('/api/documents-relevance', [DocumentController::class, 'documentsByRelevance']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['role:Administrador']], function () {
    // Rutas protegidas para Administrador
    Route::get('/admin', [AdminController::class, 'index']);
});

Route::patch('/documents/{id}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
Route::patch('/documents/{id}/reject', [DocumentController::class, 'reject'])->name('documents.reject');

require __DIR__.'/auth.php';
