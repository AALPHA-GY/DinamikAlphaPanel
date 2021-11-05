<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - LARAVEL 8
|--------------------------------------------------------------------------
*/

Route::get('/', [App\Http\Controllers\Sistem::class,'index']);
Route::post('/kolon-ekle', [App\Http\Controllers\Sistem::class,'kolonAdd'])->name('kolon-ekle');
Route::post('/sutun-ekle', [App\Http\Controllers\Sistem::class,'sutunAdd'])->name('sutun-ekle');
Route::post('/kolon-guncelle', [App\Http\Controllers\Sistem::class,'kolonUpp'])->name('kolon-guncelle');
Route::post('/tasima', [App\Http\Controllers\Sistem::class,'tasima'])->name('tasima');
