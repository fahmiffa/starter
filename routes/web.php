<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\Home;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::prefix('dashboard')->middleware('auth','isRole')->name('dashboard.')->group(function () {
    Route::get('/', [Home::class, 'index'])->name('home');
    Route::get('setting', [Home::class, 'setting'])->name('setting');
    Route::post('/pass', [Home::class, 'pass'])->name('pass');
    Route::resource('items', ItemsController::class);
    Route::resource('categori', CategoriController::class);
    Route::resource('unit', UnitController::class);
    Route::resource('stok', StokController::class);
});
