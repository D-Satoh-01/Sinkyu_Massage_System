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
  Route::get('/clinic-users-info/cui-home/registration', [ClinicUserController::class, 'create'])->name('cui-registration');
  Route::post('/clinic-users-info/cui-home/registration/confirm', [ClinicUserController::class, 'confirm'])->name('cui-registration.confirm');
  Route::post('/clinic-users-info/cui-home/registration/store', [ClinicUserController::class, 'store'])->name('cui-registration.store');
  
  // 編集機能
  Route::get('/clinic-users-info/cui-home/edit/{id}', [ClinicUserController::class, 'edit'])->name('cui-edit');
  Route::post('/clinic-users-info/cui-home/edit/confirm', [ClinicUserController::class, 'editConfirm'])->name('cui-edit.confirm');
  Route::post('/clinic-users-info/cui-home/edit/update', [ClinicUserController::class, 'update'])->name('cui-edit.update');
  
  // 削除機能
  Route::delete('/clinic-users-info/cui-delete/{id}', [ClinicUserController::class, 'destroy'])->name('cui-delete');

  // 利用者関連情報ルート
  Route::get('/clinic-users-info/cui-insurances-info/{id}', [ClinicUserController::class, 'ciiHome'])->name('cui-insurances-info');
  Route::get('/clinic-users-info/cui-insurances-info/{id}/registration', [ClinicUserController::class, 'ciiRegistration'])->name('cui-insurances-info.registration');
  Route::post('/clinic-users-info/cui-insurances-info/{id}/confirm', [ClinicUserController::class, 'insuranceConfirm'])->name('cui-insurances-info.confirm');
  Route::post('/clinic-users-info/cui-insurances-info/{id}/store', [ClinicUserController::class, 'insuranceStore'])->name('cui-insurances-info.store');
  Route::get('/clinic-users-info/cui-insurances-info/{id}/edit/{insurance_id}', [ClinicUserController::class, 'insuranceEdit'])->name('cui-insurances-info.edit');
    // cii編集機能
    Route::get('/clinic-users-info/cui-insurances-info/{id}/cii-edit/{insurance_id}', [ClinicUserController::class, 'insuranceEdit'])->name('cii-edit');
    Route::post('/clinic-users-info/cui-insurances-info/{id}/cii-edit/{insurance_id}/confirm', [ClinicUserController::class, 'insuranceEditConfirm'])->name('cii-edit.confirm');
    Route::post('/clinic-users-info/cui-insurances-info/{id}/cii-edit/{insurance_id}', [ClinicUserController::class, 'insuranceUpdate'])->name('cii-edit.update');
  // cii複製機能
  Route::get('/clinic-users-info/cui-insurances-info/{id}/duplicate/{insurance_id}', [ClinicUserController::class, 'insuranceDuplicateForm'])->name('cui-insurances-info.duplicate');
  Route::post('/clinic-users-info/cui-insurances-info/{id}/duplicate/{insurance_id}/confirm', [ClinicUserController::class, 'insuranceDuplicateConfirm'])->name('cui-insurances-info.duplicate.confirm');
  Route::post('/clinic-users-info/cui-insurances-info/{id}/duplicate/{insurance_id}/store', [ClinicUserController::class, 'insuranceDuplicateStore'])->name('cui-insurances-info.duplicate.store');
  Route::delete('/clinic-users-info/cui-insurances-info/{id}/delete/{insurance_id}', [ClinicUserController::class, 'insuranceDestroy'])->name('cui-insurances-info.delete');
  Route::get('/clinic-users-info/cui-insurances-info/{id}/print-history', [ClinicUserController::class, 'printInsuranceHistory'])->name('cui-insurances-info.print-history');

  // 同意医師履歴（あんま・マッサージ）
  Route::get('/clinic-users-info/cui-consenting-doctor-history-massage/{id}', [ClinicUserController::class, 'ccdhmHome'])->name('cui-consenting-doctor-history-massage');
  Route::get('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/registration', [ClinicUserController::class, 'ccdhmRegistration'])->name('cui-consenting-doctor-history-massage.registration');
  Route::post('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/confirm', [ClinicUserController::class, 'ccdhmConfirm'])->name('cui-consenting-doctor-history-massage.confirm');
  Route::post('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/store', [ClinicUserController::class, 'ccdhmStore'])->name('cui-consenting-doctor-history-massage.store');
  Route::get('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/edit/{history_id}', [ClinicUserController::class, 'ccdhmEdit'])->name('cui-consenting-doctor-history-massage.edit');
  Route::post('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/edit/{history_id}/confirm', [ClinicUserController::class, 'ccdhmEditConfirm'])->name('cui-consenting-doctor-history-massage.edit.confirm');
  Route::post('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/edit/{history_id}', [ClinicUserController::class, 'ccdhmUpdate'])->name('cui-consenting-doctor-history-massage.update');
  Route::get('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/duplicate/{history_id}', [ClinicUserController::class, 'ccdhmDuplicateForm'])->name('cui-consenting-doctor-history-massage.duplicate');
  Route::post('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/duplicate/{history_id}/confirm', [ClinicUserController::class, 'ccdhmDuplicateConfirm'])->name('cui-consenting-doctor-history-massage.duplicate.confirm');
  Route::post('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/duplicate/{history_id}/store', [ClinicUserController::class, 'ccdhmDuplicateStore'])->name('cui-consenting-doctor-history-massage.duplicate.store');
  Route::delete('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/delete/{history_id}', [ClinicUserController::class, 'ccdhmDestroy'])->name('cui-consenting-doctor-history-massage.delete');
  Route::get('/clinic-users-info/cui-consenting-doctor-history-massage/{id}/print-history', [ClinicUserController::class, 'printCcdhmHistory'])->name('cui-consenting-doctor-history-massage.print-history');

  Route::get('/clinic-users-info/cui-consenting-doctor-history-acupuncture/{id}', [ClinicUserController::class, 'ccdhaHome'])->name('cui-consenting-doctor-history-acupuncture');
  Route::get('/clinic-users-info/cui-plans-info/{id}', [ClinicUserController::class, 'cpiHome'])->name('cui-plans-info');
});

require __DIR__.'/auth.php';
