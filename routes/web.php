<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/dashboard', function () {
    // dd(Auth::user());

    $users = [
    [ 'id' => 1, 'name' => 'Alice',   'email' => 'alice@example.com',   'age' => 30 ],
    [ 'id' => 2, 'name' => 'Bob',     'email' => 'bob@example.com',     'age' => 25 ],
    [ 'id' => 3, 'name' => 'Charlie', 'email' => 'charlie@example.com', 'age' => 35 ],
    [ 'id' => 4, 'name' => 'David',   'email' => 'david@example.com',   'age' => 28 ],
    [ 'id' => 5, 'name' => 'Eve',     'email' => 'eve@example.com',     'age' => 22 ],
    [ 'id' => 6, 'name' => 'Frank',   'email' => 'frank@example.com',   'age' => 29 ],
    [ 'id' => 7, 'name' => 'Grace',   'email' => 'grace@example.com',   'age' => 27 ],
    [ 'id' => 8, 'name' => 'Heidi',   'email' => 'heidi@example.com',   'age' => 33 ],
];

    return view('dashboard',compact('users'));
})->middleware('auth');