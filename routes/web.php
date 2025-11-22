<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PublicPrescriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;    

Route::get('/', function () {
    if (auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});
//---------------RUTAS PROTEGIDAS, SOLO MEDICOS -----------------//
Route::middleware(['auth', 'verified'])->group(function () {
    //panel principal
    Route::get('/dashboard', [PrescriptionController::class, 'index'])
        ->name('dashboard');

    //formulario para generar nueva receta, solo rp y notas
    Route::get('/prescriptions/create', [PrescriptionController::class, 'create'])
        ->name('prescriptions.create');

    //guarda nueva receta, queda en estado pendiente
    Route::post('/prescriptions', [PrescriptionController::class, 'store'])
        ->name('prescriptions.store');

    //cancelar recetas pendientes
    Route::post('/prescriptions/{prescription}/cancel', [PrescriptionController::class, 'cancel'])
        ->name('prescriptions.cancel');

    //Cambiar estado de receta a entregada
    Route::post('/prescriptions/{prescription}/generate', [PrescriptionController::class, 'generate'])
        ->name('prescriptions.generate');

    //ver pdf en el navegador
    Route::get('/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'viewPdf'])
        ->name('prescriptions.pdf.view');

    //descargar pdf
    Route::get('/prescriptions/{prescription}/download', [PrescriptionController::class, 'downloadPdf'])
        ->name('prescriptions.pdf.download');

    //Boton para eliminar recetas finalizadas
    Route::delete('/prescriptions/{prescription}', [PrescriptionController::class, 'destroy'])
        ->name('prescriptions.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//---------------RUTAS PUBLICAS, PARA PACIENTES -----------------//
Route::get('/r/{token}', [PublicPrescriptionController::class, 'showPatientForm'])
    ->name('prescriptions.patient.form');

Route::post('/r/{token}', [PublicPrescriptionController::class, 'submitPatientForm'])
    ->name('prescriptions.patient.submit');

Route::get('/v/{token}', [PublicPrescriptionController::class, 'verify'])
    ->name('prescriptions.verify');

require __DIR__.'/auth.php';
