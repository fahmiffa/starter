<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\Home;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'loginForm'])->middleware('guest');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::prefix('dashboard')->middleware('auth')->name('dashboard.')->group(function () {
    Route::get('/', [Home::class, 'index'])->name('home');
    Route::get('/chart-json', [Home::class, 'chart'])->name('chart.sales');
    Route::get('/transaksi', [Home::class, 'trans'])->name('trans');
    Route::get('/riwayat', [Home::class, 'riwayat'])->name('riwayat');
    Route::post('/transaksi', [Home::class, 'transPost'])->name('trans');
    Route::get('setting', [Home::class, 'setting'])->name('setting');
    Route::post('/pass', [Home::class, 'pass'])->name('pass');
    Route::resource('items', ItemsController::class);
    Route::get('/items-json', [ItemsController::class, 'itemJson'])->name('item.json');
    Route::resource('categori', CategoriController::class);
    Route::get('/categori-json', [CategoriController::class, 'categoriJson'])->name('categori.json');
    Route::resource('unit', UnitController::class);
    Route::get('/unit-json', [UnitController::class, 'unitJson'])->name('unit.json');
    Route::resource('stok', StokController::class);
    Route::get('/stok-json', [StokController::class, 'stokJson'])->name('stok.json');
    Route::get('/laporan', [Home::class, 'laporan'])->name('laporan');
    Route::get('/laporan-json/{tipe}/{tahun}', [Home::class, 'laporanJson'])->name('laporan.json');
    Route::post('/laporan', [Home::class, 'laporanStore'])->name('laporanStore');

    Route::middleware('isRole')->group(function () {
        Route::get('/app', [Home::class, 'app'])->name('app');
        Route::post('/app', [Home::class, 'store'])->name('store');
        Route::put('/app/{id}/{user}', [Home::class, 'update'])->name('update');
        Route::delete('/app/{id}/{user}', [Home::class, 'destroy'])->name('destroy');
        Route::get('/app-json', [Home::class, 'appJson'])->name('app.json');
    });
});
