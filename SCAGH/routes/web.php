<?php

use App\Livewire\Horarios\Horarios;
use Illuminate\Support\Facades\Route;


Route::get('/', Horarios::class);
Route::get('/Horarios', Horarios::class);
