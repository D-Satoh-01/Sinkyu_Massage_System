<?php
// routes/web.php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClinicUserController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\ConsentMassageController;
use App\Http\Controllers\ConsentAcupunctureController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\TherapistsController;
use App\Http\Controllers\CareManagersController;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

	Route::view('/index', 'index')->name('index');

	Route::view('/master-data/index', 'master-data.master-data_index')->name('master-data.index');

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

  // 施術者情報
  Route::get('/master-data/therapists/index', [TherapistsController::class, 'index'])->name('therapists.index');
  Route::get('/master-data/therapists/create', [TherapistsController::class, 'create'])->name('therapists.create');
  Route::post('/master-data/therapists/confirm', [TherapistsController::class, 'confirm'])->name('therapists.confirm');
  Route::post('/master-data/therapists/store', [TherapistsController::class, 'store'])->name('therapists.store');

  Route::get('/master-data/therapists/{id}/edit', [TherapistsController::class, 'edit'])->name('therapists.edit');
  Route::post('/master-data/therapists/{id}/edit/confirm', [TherapistsController::class, 'editConfirm'])->name('therapists.edit.confirm');
  Route::post('/master-data/therapists/{id}/update', [TherapistsController::class, 'update'])->name('therapists.update');

  Route::delete('/master-data/therapists/{id}', [TherapistsController::class, 'destroy'])->name('therapists.delete');

  // ケアマネ情報
  Route::get('/master-data/caremanagers/index', [CareManagersController::class, 'index'])->name('caremanagers.index');
  Route::get('/master-data/caremanagers/create', [CareManagersController::class, 'create'])->name('caremanagers.create');
  Route::post('/master-data/caremanagers/confirm', [CareManagersController::class, 'confirm'])->name('caremanagers.confirm');
  Route::post('/master-data/caremanagers/store', [CareManagersController::class, 'store'])->name('caremanagers.store');

  Route::get('/master-data/caremanagers/{id}/edit', [CareManagersController::class, 'edit'])->name('caremanagers.edit');
  Route::post('/master-data/caremanagers/{id}/edit/confirm', [CareManagersController::class, 'editConfirm'])->name('caremanagers.edit.confirm');
  Route::post('/master-data/caremanagers/{id}/update', [CareManagersController::class, 'update'])->name('caremanagers.update');

  Route::delete('/master-data/caremanagers/{id}', [CareManagersController::class, 'destroy'])->name('caremanagers.delete');

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
  Route::get('/master-data/clinic-users/{id}/insurances', [InsuranceController::class, 'index'])->name('clinic-users.insurances.index');
  Route::get('/master-data/clinic-users/{id}/insurances/create', [InsuranceController::class, 'create'])->name('clinic-users.insurances.create');
  Route::post('/master-data/clinic-users/{id}/insurances/confirm', [InsuranceController::class, 'confirm'])->name('clinic-users.insurances.confirm');
  Route::post('/master-data/clinic-users/{id}/insurances/store', [InsuranceController::class, 'store'])->name('clinic-users.insurances.store');

  Route::get('/master-data/clinic-users/{id}/insurances/{insurance_id}/edit', [InsuranceController::class, 'edit'])->name('clinic-users.insurances.edit');
  Route::post('/master-data/clinic-users/{id}/insurances/{insurance_id}/edit/confirm', [InsuranceController::class, 'editConfirm'])->name('clinic-users.insurances.edit.confirm');
  Route::post('/master-data/clinic-users/{id}/insurances/{insurance_id}/edit/update', [InsuranceController::class, 'update'])->name('clinic-users.insurances.edit.update');

  Route::get('/master-data/clinic-users/{id}/insurances/{insurance_id}/duplicate', [InsuranceController::class, 'duplicateForm'])->name('clinic-users.insurances.duplicate');
  Route::post('/master-data/clinic-users/{id}/insurances/{insurance_id}/duplicate/confirm', [InsuranceController::class, 'duplicateConfirm'])->name('clinic-users.insurances.duplicate.confirm');
  Route::post('/master-data/clinic-users/{id}/insurances/{insurance_id}/duplicate/store', [InsuranceController::class, 'duplicateStore'])->name('clinic-users.insurances.duplicate.store');

  Route::delete('/master-data/clinic-users/{id}/insurances/{insurance_id}', [InsuranceController::class, 'destroy'])->name('clinic-users.insurances.delete');
  Route::get('/master-data/clinic-users/{id}/insurances/print-history', [InsuranceController::class, 'print'])->name('clinic-users.insurances.print-history');

  // 同意医師履歴（あんま・マッサージ）
  Route::get('/master-data/clinic-users/{id}/consents-massage', [ConsentMassageController::class, 'index'])->name('clinic-users.consents-massage.index');
  Route::get('/master-data/clinic-users/{id}/consents-massage/create', [ConsentMassageController::class, 'create'])->name('clinic-users.consents-massage.create');
  Route::post('/master-data/clinic-users/{id}/consents-massage/confirm', [ConsentMassageController::class, 'confirm'])->name('clinic-users.consents-massage.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-massage/store', [ConsentMassageController::class, 'store'])->name('clinic-users.consents-massage.store');

  Route::get('/master-data/clinic-users/{id}/consents-massage/{history_id}/edit', [ConsentMassageController::class, 'edit'])->name('clinic-users.consents-massage.edit');
  Route::post('/master-data/clinic-users/{id}/consents-massage/{history_id}/edit/confirm', [ConsentMassageController::class, 'editConfirm'])->name('clinic-users.consents-massage.edit.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-massage/{history_id}/edit/update', [ConsentMassageController::class, 'update'])->name('clinic-users.consents-massage.edit.update');

  Route::get('/master-data/clinic-users/{id}/consents-massage/{history_id}/duplicate', [ConsentMassageController::class, 'duplicateForm'])->name('clinic-users.consents-massage.duplicate');
  Route::post('/master-data/clinic-users/{id}/consents-massage/{history_id}/duplicate/confirm', [ConsentMassageController::class, 'duplicateConfirm'])->name('clinic-users.consents-massage.duplicate.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-massage/{history_id}/duplicate/store', [ConsentMassageController::class, 'duplicateStore'])->name('clinic-users.consents-massage.duplicate.store');

  Route::delete('/master-data/clinic-users/{id}/consents-massage/{history_id}', [ConsentMassageController::class, 'destroy'])->name('clinic-users.consents-massage.delete');
  Route::get('/master-data/clinic-users/{id}/consents-massage/print-history', [ConsentMassageController::class, 'print'])->name('clinic-users.consents-massage.print-history');

  // 同意医師履歴（鍼灸）
  Route::get('/master-data/clinic-users/{id}/consents-acupuncture', [ConsentAcupunctureController::class, 'index'])->name('clinic-users.consents-acupuncture.index');
  Route::get('/master-data/clinic-users/{id}/consents-acupuncture/create', [ConsentAcupunctureController::class, 'create'])->name('clinic-users.consents-acupuncture.registration');
  Route::post('/master-data/clinic-users/{id}/consents-acupuncture/confirm', [ConsentAcupunctureController::class, 'confirm'])->name('clinic-users.consents-acupuncture.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-acupuncture/store', [ConsentAcupunctureController::class, 'store'])->name('clinic-users.consents-acupuncture.store');

  Route::get('/master-data/clinic-users/{id}/consents-acupuncture/{history_id}/edit', [ConsentAcupunctureController::class, 'edit'])->name('clinic-users.consents-acupuncture.edit');
  Route::post('/master-data/clinic-users/{id}/consents-acupuncture/{history_id}/edit/confirm', [ConsentAcupunctureController::class, 'editConfirm'])->name('clinic-users.consents-acupuncture.edit.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-acupuncture/{history_id}/edit/update', [ConsentAcupunctureController::class, 'update'])->name('clinic-users.consents-acupuncture.edit.update');

  Route::get('/master-data/clinic-users/{id}/consents-acupuncture/{history_id}/duplicate', [ConsentAcupunctureController::class, 'duplicateForm'])->name('clinic-users.consents-acupuncture.duplicate');
  Route::post('/master-data/clinic-users/{id}/consents-acupuncture/{history_id}/duplicate/confirm', [ConsentAcupunctureController::class, 'duplicateConfirm'])->name('clinic-users.consents-acupuncture.duplicate.confirm');
  Route::post('/master-data/clinic-users/{id}/consents-acupuncture/{history_id}/duplicate/store', [ConsentAcupunctureController::class, 'duplicateStore'])->name('clinic-users.consents-acupuncture.duplicate.store');

  Route::delete('/master-data/clinic-users/{id}/consents-acupuncture/{history_id}', [ConsentAcupunctureController::class, 'destroy'])->name('clinic-users.consents-acupuncture.delete');
  Route::get('/master-data/clinic-users/{id}/consents-acupuncture/print-history', [ConsentAcupunctureController::class, 'print'])->name('clinic-users.consents-acupuncture.print-history');

  // 計画情報
  Route::get('/master-data/clinic-users/{id}/plans', [PlanController::class, 'index'])->name('clinic-users.plans.index');
  Route::get('/master-data/clinic-users/{id}/plans/create', [PlanController::class, 'create'])->name('clinic-users.plans.create');
  Route::post('/master-data/clinic-users/{id}/plans/confirm', [PlanController::class, 'confirm'])->name('clinic-users.plans.confirm');
  Route::post('/master-data/clinic-users/{id}/plans/store', [PlanController::class, 'store'])->name('clinic-users.plans.store');

  Route::get('/master-data/clinic-users/{id}/plans/{plan_id}/edit', [PlanController::class, 'edit'])->name('clinic-users.plans.edit');
  Route::post('/master-data/clinic-users/{id}/plans/{plan_id}/edit/confirm', [PlanController::class, 'editConfirm'])->name('clinic-users.plans.edit.confirm');
  Route::post('/master-data/clinic-users/{id}/plans/{plan_id}/edit/update', [PlanController::class, 'update'])->name('clinic-users.plans.edit.update');

  Route::get('/master-data/clinic-users/{id}/plans/{plan_id}/duplicate', [PlanController::class, 'duplicateForm'])->name('clinic-users.plans.duplicate');
  Route::post('/master-data/clinic-users/{id}/plans/{plan_id}/duplicate/confirm', [PlanController::class, 'duplicateConfirm'])->name('clinic-users.plans.duplicate.confirm');
  Route::post('/master-data/clinic-users/{id}/plans/{plan_id}/duplicate/store', [PlanController::class, 'duplicateStore'])->name('clinic-users.plans.duplicate.store');

  Route::delete('/master-data/clinic-users/{id}/plans/{plan_id}', [PlanController::class, 'destroy'])->name('clinic-users.plans.delete');
  Route::get('/master-data/clinic-users/{id}/plans/print-history', [PlanController::class, 'print'])->name('clinic-users.plans.print-history');
});

require __DIR__.'/auth.php';
