<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParameterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('parameters', [ParameterController::class, 'index'])->name('parameters.index');
Route::get('parameters/create', [ParameterController::class, 'create'])->name('parameters.create');
Route::post('parameters', [ParameterController::class, 'store'])->name('parameters.store');
Route::post('parameters/{id}/upload', [ParameterController::class, 'upload'])->name('parameters.upload');
Route::delete('parameters/{id}/delete/{type}', [ParameterController::class, 'deleteImage'])->name('parameters.deleteImage');
