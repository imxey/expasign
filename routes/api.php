<?php
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Http\Controllers\Submit;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamRegistrationController;
use App\Http\Controllers\Edutime;

Route::post('/team-regist/handle', [TeamRegistrationController::class, 'handleRegistration']);

Route::post('/submission/handle', [Submit::class, 'handleSubmission'])->name('submit.handle');
Route::post('/edutime/handle', [Edutime::class, 'handleEdutime'])->name('edutime.handle');