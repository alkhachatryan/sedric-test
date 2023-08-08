<?php

use App\Http\Controllers\AudioController;
use Illuminate\Support\Facades\Route;

Route::get('/audios', [AudioController::class, 'index'])->name('qaq');
Route::post('/audios', [AudioController::class, 'create']);
