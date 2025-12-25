<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReporteAsistenciaController;

use App\Livewire\Horarios\Horarios;
use App\Livewire\Semestres\Semestres;
use App\Livewire\Cursos\Cursos;
use App\Livewire\Docentes\Docentes;
use App\Livewire\Estudiantes\Estudiantes;
use App\Livewire\Asistencias\Asistencias;
use App\Livewire\Carreras\Carreras;
use App\Livewire\Reportes\Reportes;
use App\Livewire\Usuarios\Usuarios;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');


    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


Route::middleware('auth')->group(function () {

    Route::get('/', Horarios::class)->name('home');

    // TODOS (1,2,3): solo Horarios
    Route::middleware('role:1,2,3')->group(function () {
        Route::get('/Horarios', Horarios::class);
    });

    // ADMIN + DOCENTE (1,2): Asistencias + Reportes + PDF
    Route::middleware('role:1,2')->group(function () {
        Route::get('/Asistencias', Asistencias::class);
        Route::get('/Reportes', Reportes::class);

        Route::get('/ReporteAsistencia/pdf/{asistencia}', [ReporteAsistenciaController::class, 'generarPDF'])
            ->name('ReporteAsistencia.pdf');
    });

    // SOLO ADMIN (1)
    Route::middleware('role:1')->group(function () {
        Route::get('/Usuarios', Usuarios::class);
        Route::get('/Semestres', Semestres::class);
        Route::get('/Cursos', Cursos::class);
        Route::get('/Docentes', Docentes::class);
        Route::get('/Estudiantes', Estudiantes::class);
        Route::get('/Carreras', Carreras::class);
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
