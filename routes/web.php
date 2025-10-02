<?php
// routes/web.php

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
	
	// 登録機能
  Route::get('/clinic-users-info/cui-home', [ClinicUserController::class, 'index'])->name('cui-home');
  Route::get('/clinic-users-info/cui-registration', [ClinicUserController::class, 'create'])->name('cui-registration');
  Route::post('/clinic-users-info/cui-registration/confirm', [ClinicUserController::class, 'confirm'])->name('cui-registration.confirm');
  Route::post('/clinic-users-info/cui-registration/store', [ClinicUserController::class, 'store'])->name('cui-registration.store');
  
  // 編集機能
  Route::get('/clinic-users-info/cui-edit/{id}', [ClinicUserController::class, 'edit'])->name('cui-edit');
  Route::post('/clinic-users-info/cui-edit/confirm', [ClinicUserController::class, 'editConfirm'])->name('cui-edit.confirm');
  Route::post('/clinic-users-info/cui-edit/update', [ClinicUserController::class, 'update'])->name('cui-edit.update');
  
  // 削除機能
  Route::delete('/clinic-users-info/cui-delete/{id}', [ClinicUserController::class, 'destroy'])->name('cui-delete');
});

require __DIR__.'/auth.php';
