<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Prefecture;

Route::get('/', function () {
	return view('auth.login');
});

Route::get('/auth/login', function () {
	return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
	return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', function () {
	$prefectures = Prefecture::all();
	return view('home', compact('prefectures'));
})->middleware('auth')->name('home');

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::view('/master-registration', 'master-registration')->name('master-registration');