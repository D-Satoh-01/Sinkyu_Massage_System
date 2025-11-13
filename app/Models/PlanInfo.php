<?php
// app/Models/PlanInfo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanInfo extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'plan_infos';

  protected $fillable = [
    'clinic_user_id',
    'evaluation_date',
    'evaluator',
    'respiration',
    'meal_assistance_level',
    'meal_assistance_note',
    'mobility_level',
    'mobility_note',
    'grooming_level',
    'grooming_note',
    'toilet_level',
    'toilet_note',
    'bathing_level',
    'bathing_note',
    'flat_walking_level',
    'flat_walking_note',
    'stairs_level',
    'stairs_note',
    'dressing_level',
    'dressing_note',
    'defecation_level',
    'defecation_note',
    'urination_level',
    'urination_note',
    'communication',
    'patient_family_request',
    'treatment_purpose',
    'rehabilitation_program',
    'home_rehabilitation',
    'improvement_changes',
    'disability_notes',
    'consent_date'
  ];

  protected $casts = [
    'evaluation_date' => 'date',
    'consent_date' => 'date'
  ];

  public function clinicUser()
  {
    return $this->belongsTo(ClinicUser::class, 'clinic_user_id');
  }
}
