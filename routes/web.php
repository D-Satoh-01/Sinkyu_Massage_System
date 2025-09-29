<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicUserController;

Route::view('/','auth.login');

Route::view('/auth/login','auth.login')->name('login');

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

	Route::view('/home', 'home')->name('home');
	
	Route::view('/master-registration/mr-home', 'master-registration.mr-home')->name('mr-home');
  Route::view('/clinic-users-info/cui-home', 'clinic-users-info.cui-home')->name('cui-home');
  Route::get('/clinic-users-info/cui-registration', [ClinicUserController::class, 'create'])->name('cui-registration');
  Route::post('/clinic-users-info/cui-registration/confirm', [ClinicUserController::class, 'confirm'])->name('cui-registration.confirm');
  Route::post('/clinic-users-info/cui-registration/store', [ClinicUserController::class, 'store'])->name('cui-registration.store');
});

require __DIR__.'/auth.php';
