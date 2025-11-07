<?php
// routes/web.php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicUserController;
use App\Http\Controllers\DoctorsController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

	Route::view('/index', 'index')->name('index');

	Route::view('/master-data/index', 'master-data.master-data-index')->name('master-data.index');

  // 医師情報
  Route::get('/master-data/doctors/index', [DoctorsController::class, 'index'])->name('doctors.index');
  Route::get('/master-data/doctors/create', [DoctorsController::class, 'create'])->name('doctors.create');
  Route::post('/master-data/doctors/confirm', [DoctorsController::class, 'confirm'])->name('doctors.confirm');
  Route::post('/master-data/doctors/store', [DoctorsController::class, 'store'])->name('doctors.store');

  Route::get('/master-data/doctors/{id}/edit', [DoctorsController::class, 'edit'])->name('doctors.edit');
  Route::post('/master-data/doctors/{id}/edit/confirm', [DoctorsController::class, 'editConfirm'])->name('doctors.edit.confirm');
  Route::post('/master-data/doctors/{id}/update', [DoctorsController::class, 'update'])->name('doctors.update');

  Route::get('/master-data/doctors/{id}/duplicate', [DoctorsController::class, 'duplicate'])->name('doctors.duplicate');
  Route::post('/master-data/doctors/duplicate/confirm', [DoctorsController::class, 'duplicateConfirm'])->name('doctors.duplicate.confirm');
  Route::post('/master-data/doctors/duplicate/store', [DoctorsController::class, 'duplicateStore'])->name('doctors.duplicate.store');

  Route::delete('/master-data/doctors/{id}', [DoctorsController::class, 'destroy'])->name('doctors.delete');

  // 利用者情報
  Route::get('/master-data/clinic-users/index', [ClinicUserController::class, 'index'])->name('clinic-users.index');
  Route::get('/master-data/clinic-users/create', [ClinicUserController::class, 'create'])->name('clinic-users.create');
  Route::post('/master-data/clinic-users/confirm', [ClinicUserController::class, 'confirm'])->name('clinic-users.confirm');
  Route::post('/master-data/clinic-users/store', [ClinicUserController::class, 'store'])->name('clinic-users.store');

  Route::get('/master-data/clinic-users/{id}/edit', [ClinicUserController::class, 'edit'])->name('clinic-users.edit');
  Route::post('/master-data/clinic-users/{id}/edit/confirm', [ClinicUserController::class, 'editConfirm'])->name('clinic-users.edit.confirm');
  Route::post('/master-data/clinic-users/{id}/edit/update', [ClinicUserController::class, 'update'])->name('clinic-users.edit.update');

  Route::delete('/master-data/clinic-users/{id}', [ClinicUserController::class, 'destroy'])->name('clinic-users.delete');

  // 保険情報
  Route::get('/master-data/clinic-users/{id}/insurances', [ClinicUserController::class, 'insurancesIndex'])->name('clinic-users.insurances.index');
  Route::get('/master-data/clinic-users/{id}/insurances/create', [ClinicUserController::class, 'insurancesCreate'])->name('clinic-users.insurances.create');
  Route::post('/master-data/clinic-users/{id}/insurances/confirm', [ClinicUserController::class, 'insurancesConfirm'])->name('clinic-users.insurances.confirm');
  Route::post('/master-data/clinic-users/{id}/insurances/store', [ClinicUserController::class, 'insurancesStore'])->name('clinic-users.insurances.store');

  Route::get('/master-data/clinic-users/{id}/insurances/{insurance_id}/edit', [ClinicUserController::class, 'insurancesEdit'])->name('clinic-users.insurances.edit');
  Route::post('/master-data/clinic-users/{id}/insurances/{insurance_id}/edit/confirm', [ClinicUserController::class, 'insurancesEditConfirm'])->name('clinic-users.insurances.edit.confirm');
  Route::post('/master-data/clinic-users/{id}/insurances/{insurance_id}/edit/update', [ClinicUserController::class, 'insurancesUpdate'])->name('clinic-users.insurances.edit.update');

  Route::get('/master-data/clinic-users/{id}/insurances/{insurance_id}/duplicate', [ClinicUserController::class, 'insurancesDuplicateForm'])->name('clinic-users.insurances.duplicate');
  Route::post('/master-data/clinic-users/{id}/insurances/{insurance_id}/duplicate/confirm', [ClinicUserController::class, 'insurancesDuplicateConfirm'])->name('clinic-users.insurances.duplicate.confirm');
  Route::post('/master-data/clinic-users/{id}/insurances/{insurance_id}/duplicate/store', [ClinicUserController::class, 'insurancesDuplicateStore'])->name('clinic-users.insurances.duplicate.store');

  Route::delete('/master-data/clinic-users/{id}/insurances/{insurance_id}', [ClinicUserController::class, 'insurancesDestroy'])->name('clinic-users.insurances.delete');
  Route::get('/master-data/clinic-users/{id}/insurances/print-history', [ClinicUserController::class, 'printInsurances'])->name('clinic-users.insurances.print-history');

  // 同意医師履歴（あんま・マッサージ）
  Route::get('/master-data/clinic-users/{id}/consents-massage', [ClinicUserController::class, 'consentsMassageIndex'])->name('clinic-users.consents-massage.index');
  Route::get('/master-data/clinic-users/{id}/consents-massage/create', [ClinicUserController::class, 'consentsMassageCreate'])->name('clinic-users.consents-massage.create');
  Route::post('/master-data/clinic-users/{id}/consents-massage/confirm', [ClinicUserController::class, 'consentsMassageConfirm'])->name('clinic-users.consents-massage.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-massage/store', [ClinicUserController::class, 'consentsMassageStore'])->name('clinic-users.consents-massage.store');

  Route::get('/master-data/clinic-users/{id}/consents-massage/{history_id}/edit', [ClinicUserController::class, 'consentsMassageEdit'])->name('clinic-users.consents-massage.edit');
  Route::post('/master-data/clinic-users/{id}/consents-massage/{history_id}/edit/confirm', [ClinicUserController::class, 'consentsMassageEditConfirm'])->name('clinic-users.consents-massage.edit.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-massage/{history_id}/edit/update', [ClinicUserController::class, 'consentsMassageUpdate'])->name('clinic-users.consents-massage.edit.update');

  Route::get('/master-data/clinic-users/{id}/consents-massage/{history_id}/duplicate', [ClinicUserController::class, 'consentsMassageDuplicateForm'])->name('clinic-users.consents-massage.duplicate');
  Route::post('/master-data/clinic-users/{id}/consents-massage/{history_id}/duplicate/confirm', [ClinicUserController::class, 'consentsMassageDuplicateConfirm'])->name('clinic-users.consents-massage.duplicate.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-massage/{history_id}/duplicate/store', [ClinicUserController::class, 'consentsMassageDuplicateStore'])->name('clinic-users.consents-massage.duplicate.store');

  Route::delete('/master-data/clinic-users/{id}/consents-massage/{history_id}', [ClinicUserController::class, 'consentsMassageDestroy'])->name('clinic-users.consents-massage.delete');
  Route::get('/master-data/clinic-users/{id}/consents-massage/print-history', [ClinicUserController::class, 'printConsentsMassage'])->name('clinic-users.consents-massage.print-history');

  // 同意医師履歴（鍼灸）
  Route::get('/master-data/clinic-users/{id}/consents-acupuncture', [ClinicUserController::class, 'consentsAcupunctureIndex'])->name('clinic-users.consents-acupuncture.index');

  // 計画情報
  Route::get('/master-data/clinic-users/{id}/plans', [ClinicUserController::class, 'plansIndex'])->name('clinic-users.plans.index');
});

require __DIR__.'/auth.php';
