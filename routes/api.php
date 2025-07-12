<?php
use App\Http\Controllers\File; 
use Illuminate\Http\Request;
use App\Http\Controllers\Regist;
use Illuminate\Support\Facades\Route;

Route::post('/upload', [File::class, 'handleUpload']);
Route::post('/callback', [Regist::class, 'handleCallback'])->name('regist.callback');