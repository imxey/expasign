<?php
use App\Http\Controllers\File; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/upload', [File::class, 'handleUpload']);