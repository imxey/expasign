<?php

use App\Http\Controllers\Submit;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test;
use Inertia\Inertia;
use App\Http\Controllers\Regist;
use Illuminate\Http\Request;
use App\Http\Controllers\Edutime;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});
Route::get('/test', [Test::class, 'index'])
    ->name('test.index');
Route::get('/register', [Regist::class, 'index'])->name('regist');
Route::get('/payment', function (Request $request) {
    return Inertia::render('Regist/saweria', [
        'id' => $request->input('id'),
        'name' => $request->input('name'),
        'nominal' => $request->input('nominal'),
    ]);
})->name('payment');
Route::get('/submission', [Submit::class, 'index'])->name('submit.index');
Route::get('/edutime', function () {
    return Inertia::render('Edutime');
})->name('edutime');
require __DIR__.'/settings.php';
