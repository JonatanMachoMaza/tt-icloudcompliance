<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;

// Rutas accesibles públicamente
Route::get('/', function () {
    return view('welcome');
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Endpoints de API con protección de AUTH
    Route::get('/api/documents', [DocumentController::class, 'apiIndex']);
    Route::get('/api/document-statistics', [DocumentController::class, 'getDocumentStatistics']);

    // Ruta para descargar documentos
    Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->name('documents.download');

    // Rutas personalizadas para aprobar y rechazar documentos
    Route::patch('/documents/approve/{id}', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::patch('/documents/reject/{id}', [DocumentController::class, 'reject'])->name('documents.reject');

    // Recurso completo de documentos: Esto genera automáticamente las rutas necesarias para CRUD
    Route::resource('documents', DocumentController::class)->except(['destroy']);

    // Definir manualmente la ruta destroy para no duplicar con resource
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Rutas para el perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para la gestión de usuarios
    Route::resource('users', UserController::class);
});

// Ruta protegida para el dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rutas de autenticación
require __DIR__.'/auth.php';
