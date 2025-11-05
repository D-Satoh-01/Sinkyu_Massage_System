<?php
// routes/web.php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicUserController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

	Route::view('/index', 'index')->name('index');

	Route::view('/master-registration/index', 'master-registration.index')->name('master-registration.index');

  // 利用者情報
  Route::get('/master-registration/clinic-users-info/index', [ClinicUserController::class, 'index'])->name('clinic-users-info.index');
  Route::get('/master-registration/clinic-users-info/registration', [ClinicUserController::class, 'create'])->name('clinic-users-info.registration');
  Route::post('/master-registration/clinic-users-info/registration/confirm', [ClinicUserController::class, 'confirm'])->name('clinic-users-info.registration.confirm');
  Route::post('/master-registration/clinic-users-info/registration/store', [ClinicUserController::class, 'store'])->name('clinic-users-info.registration.store');

  Route::get('/master-registration/clinic-users-info/{id}/edit', [ClinicUserController::class, 'edit'])->name('clinic-users-info.edit');
  Route::post('/master-registration/clinic-users-info/{id}/edit/confirm', [ClinicUserController::class, 'editConfirm'])->name('clinic-users-info.edit.confirm');
  Route::post('/master-registration/clinic-users-info/{id}/edit/update', [ClinicUserController::class, 'update'])->name('clinic-users-info.edit.update');

  Route::delete('/master-registration/clinic-users-info/{id}', [ClinicUserController::class, 'destroy'])->name('clinic-users-info.delete');

  // 保険情報
  Route::get('/master-registration/clinic-users-info/{id}/insurances-info', [ClinicUserController::class, 'ciiIndex'])->name('clinic-users-info.insurances-info.index');
  Route::get('/master-registration/clinic-users-info/{id}/insurances-info/registration', [ClinicUserController::class, 'ciiRegistration'])->name('clinic-users-info.insurances-info.registration');
  Route::post('/master-registration/clinic-users-info/{id}/insurances-info/confirm', [ClinicUserController::class, 'insuranceConfirm'])->name('clinic-users-info.insurances-info.confirm');
  Route::post('/master-registration/clinic-users-info/{id}/insurances-info/store', [ClinicUserController::class, 'insuranceStore'])->name('clinic-users-info.insurances-info.store');

  Route::get('/master-registration/clinic-users-info/{id}/insurances-info/{insurance_id}/edit', [ClinicUserController::class, 'insuranceEdit'])->name('clinic-users-info.insurances-info.edit');
  Route::post('/master-registration/clinic-users-info/{id}/insurances-info/{insurance_id}/edit/confirm', [ClinicUserController::class, 'insuranceEditConfirm'])->name('clinic-users-info.insurances-info.edit.confirm');
  Route::post('/master-registration/clinic-users-info/{id}/insurances-info/{insurance_id}/edit/update', [ClinicUserController::class, 'insuranceUpdate'])->name('clinic-users-info.insurances-info.edit.update');

  Route::get('/master-registration/clinic-users-info/{id}/insurances-info/{insurance_id}/duplicate', [ClinicUserController::class, 'insuranceDuplicateForm'])->name('clinic-users-info.insurances-info.duplicate');
  Route::post('/master-registration/clinic-users-info/{id}/insurances-info/{insurance_id}/duplicate/confirm', [ClinicUserController::class, 'insuranceDuplicateConfirm'])->name('clinic-users-info.insurances-info.duplicate.confirm');
  Route::post('/master-registration/clinic-users-info/{id}/insurances-info/{insurance_id}/duplicate/store', [ClinicUserController::class, 'insuranceDuplicateStore'])->name('clinic-users-info.insurances-info.duplicate.store');

  Route::delete('/master-registration/clinic-users-info/{id}/insurances-info/{insurance_id}', [ClinicUserController::class, 'insuranceDestroy'])->name('clinic-users-info.insurances-info.delete');
  Route::get('/master-registration/clinic-users-info/{id}/insurances-info/print-history', [ClinicUserController::class, 'printInsuranceHistory'])->name('clinic-users-info.insurances-info.print-history');

  // 同意医師履歴（あんま・マッサージ）
  Route::get('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage', [ClinicUserController::class, 'ccdhmIndex'])->name('clinic-users-info.consenting-doctor-history-massage.index');
  Route::get('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/registration', [ClinicUserController::class, 'ccdhmRegistration'])->name('clinic-users-info.consenting-doctor-history-massage.registration');
  Route::post('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/confirm', [ClinicUserController::class, 'ccdhmConfirm'])->name('clinic-users-info.consenting-doctor-history-massage.confirm');
  Route::post('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/store', [ClinicUserController::class, 'ccdhmStore'])->name('clinic-users-info.consenting-doctor-history-massage.store');

  Route::get('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/{history_id}/edit', [ClinicUserController::class, 'ccdhmEdit'])->name('clinic-users-info.consenting-doctor-history-massage.edit');
  Route::post('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/{history_id}/edit/confirm', [ClinicUserController::class, 'ccdhmEditConfirm'])->name('clinic-users-info.consenting-doctor-history-massage.edit.confirm');
  Route::post('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/{history_id}/edit/update', [ClinicUserController::class, 'ccdhmUpdate'])->name('clinic-users-info.consenting-doctor-history-massage.edit.update');

  Route::get('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/{history_id}/duplicate', [ClinicUserController::class, 'ccdhmDuplicateForm'])->name('clinic-users-info.consenting-doctor-history-massage.duplicate');
  Route::post('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/{history_id}/duplicate/confirm', [ClinicUserController::class, 'ccdhmDuplicateConfirm'])->name('clinic-users-info.consenting-doctor-history-massage.duplicate.confirm');
  Route::post('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/{history_id}/duplicate/store', [ClinicUserController::class, 'ccdhmDuplicateStore'])->name('clinic-users-info.consenting-doctor-history-massage.duplicate.store');

  Route::delete('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/{history_id}', [ClinicUserController::class, 'ccdhmDestroy'])->name('clinic-users-info.consenting-doctor-history-massage.delete');
  Route::get('/master-registration/clinic-users-info/{id}/consenting-doctor-history-massage/print-history', [ClinicUserController::class, 'printCcdhmHistory'])->name('clinic-users-info.consenting-doctor-history-massage.print-history');

  // 同意医師履歴（鍼灸）
  Route::get('/master-registration/clinic-users-info/{id}/consenting-doctor-history-acupuncture', [ClinicUserController::class, 'ccdhaIndex'])->name('clinic-users-info.consenting-doctor-history-acupuncture.index');

  // 計画情報
  Route::get('/master-registration/clinic-users-info/{id}/plans-info', [ClinicUserController::class, 'cpiIndex'])->name('clinic-users-info.plans-info.index');
});

require __DIR__.'/auth.php';
