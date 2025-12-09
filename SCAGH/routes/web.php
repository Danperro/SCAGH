<?php

use App\Livewire\Cursos\Cursos;
use App\Livewire\Docentes\Docentes;
use App\Livewire\Estudiantes\Estudiantes;
use App\Livewire\Horarios\Horarios;
use App\Livewire\Reportes\Reportes;
use App\Livewire\Usuarios\Usuarios;
use App\Livewire\Asistencia\Asistencia;
use App\Livewire\Semestres\Semestres;
use Illuminate\Support\Facades\Route;


Route::get('/', Horarios::class);
Route::get('/Horarios', Horarios::class);
Route::get('/Semestres', Semestres::class);
Route::get('/Cursos', Cursos::class);
Route::get('/Docentes', Docentes::class);
Route::get('/Estudiantes', Estudiantes::class);
Route::get('/Asistencia', Asistencia::class);
Route::get('/Reportes', Reportes::class);
Route::get('/Usuarios', Usuarios::class);
