<?php
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Http\Controllers\Regist;
use App\Http\Controllers\Submit;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Edutime;

Route::post('/callback', [Regist::class, 'handleCallback'])->name('regist.callback');
Route::post('/regist/handle', [Regist::class, 'handleRegist'])->name('regist.handle');
Route::post('/submission/handle', [Submit::class, 'handleSubmission'])->name('submit.handle');
Route::post('/edutime/handle', [Edutime::class, 'handleEdutime'])->name('edutime.handle');