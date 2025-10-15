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
    'insurers_id',
    'insurance_type_1_id',
    'insurance_type_2_id',
    'insurance_type_3_id',
    'self_or_family_id',
    'insured_number',
    'code_number',
    'account_number',
    'license_acquisition_date',
    'certification_date',
    'issue_date',
    'expenses_borne_ratio_id',
    'expiry_date',
    'is_redeemed',
    'insured_name',
    'relationship_with_clinic_user_id',
    'is_healthcare_subsidized',
    'public_funds_payer_code',
    'public_funds_recipient_code',
    'locality_code',
    'recipient_code'
  ];

  protected $casts = [
    'license_acquisition_date' => 'date',
    'certification_date' => 'date',
    'issue_date' => 'date',
    'expiry_date' => 'date',
    'is_redeemed' => 'boolean',
    'is_healthcare_subsidized' => 'boolean'
  ];

  public function clinicUser()
  {
    return $this->belongsTo(ClinicUserModel::class, 'clinic_user_id');
  }

  public function insurer()
  {
    return $this->belongsTo(Insurer::class, 'insurers_id');
  }
}
