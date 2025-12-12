<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
  protected $fillable = [
    'clinic_user_id',
    'year_month',
    'insurer_id',
    'insured_name',
    'therapy_period_start',
    'therapy_period_end',
    'treatment_type',
    'treatment_dates',
    'total_amount',
    'selfpay_amount',
    'insurance_billing_amount',
    'deposit_amount',
    'deposit_date',
  ];

  protected $casts = [
    'treatment_dates' => 'array',
    'therapy_period_start' => 'date',
    'therapy_period_end' => 'date',
    'deposit_date' => 'date',
  ];

  public function clinicUser()
  {
    return $this->belongsTo(ClinicUser::class);
  }

  public function insurer()
  {
    return $this->belongsTo(Insurer::class, 'insurer_id');
  }
}
