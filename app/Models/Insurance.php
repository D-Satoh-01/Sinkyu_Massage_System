<?php
// app/Models/Insurance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'insurances';

  protected $fillable = [
    'clinic_user_id',
    'insurer_id',
    'insurance_type_1',
    'insurance_type_2',
    'insurance_type_3',
    'insured_person_type',
    'insured_number',
    'symbol',
    'number',
    'qualification_date',
    'certification_date',
    'issue_date',
    'copayment_rate',
    'expiration_date',
    'reimbursement_target',
    'insured_person_name',
    'relationship',
    'medical_assistance_target',
    'public_burden_number',
    'public_recipient_number',
    'municipal_code',
    'recipient_number'
  ];

  protected $casts = [
    'qualification_date' => 'date',
    'certification_date' => 'date',
    'issue_date' => 'date',
    'expiration_date' => 'date',
    'reimbursement_target' => 'boolean',
    'medical_assistance_target' => 'boolean'
  ];

  public function clinicUser()
  {
    return $this->belongsTo(ClinicUserModel::class, 'clinic_user_id');
  }

  public function insurer()
  {
    return $this->belongsTo(Insurer::class, 'insurer_id');
  }
}
