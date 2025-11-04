<?php
// app/Models/ConsentingDoctorHistoryMassage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentingDoctorHistoryMassage extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'consenting_doctor_history_massage';

  protected $fillable = [
    'clinic_user_id',
    'consenting_doctor_name',
    'consenting_date',
    'consenting__start_date',
    'consenting_end_date',
    'benefit_period_start_date',
    'benefit_period_end_date',
    'first_care_date',
    'injury_and_illness_name_id',
    'reconsenting_expiry',
    'bill_category_id',
    'outcome_id',
    'is_symptom_1',
    'is_symptom_2',
    'symtom_2_addendum',
    'is_symptom_3',
    'symtom_3_addendum',
    'is_therapy_type_1',
    'is_therapy_type_2',
    'is_housecall_required',
    'housecall_reason_id',
    'housecall_reason_addendum',
    'care_level',
    'notes',
    'therapy_period',
    'first_therapy_content_id',
    'condition_id',
    'work_scope_type_id',
    'onset_and_injury_date'
  ];

  protected $casts = [
    'consenting_date' => 'date',
    'consenting__start_date' => 'date',
    'consenting_end_date' => 'date',
    'benefit_period_start_date' => 'date',
    'benefit_period_end_date' => 'date',
    'first_care_date' => 'date',
    'reconsenting_expiry' => 'date',
    'onset_and_injury_date' => 'date',
    'is_symptom_1' => 'boolean',
    'is_symptom_2' => 'boolean',
    'is_symptom_3' => 'boolean',
    'is_therapy_type_1' => 'boolean',
    'is_therapy_type_2' => 'boolean',
    'is_housecall_required' => 'boolean'
  ];

  // アクセサ: consenting_start_date (アンダースコア2つのフィールドにアクセスするため)
  public function getConsentingStartDateAttribute()
  {
    return $this->consenting__start_date;
  }

  // ミューテータ: consenting_start_date
  public function setConsentingStartDateAttribute($value)
  {
    $this->attributes['consenting__start_date'] = $value;
  }

  public function clinicUser()
  {
    return $this->belongsTo(ClinicUserModel::class, 'clinic_user_id');
  }
}
