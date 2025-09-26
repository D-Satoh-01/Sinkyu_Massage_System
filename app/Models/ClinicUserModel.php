<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicUserModel extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'clinic_users';
  
  protected $fillable = [
    'clinic_user_name',
    'furigana',
    'birthday',
    'age',
    'gender_id',
    'postal_code',
    'address_1',
    'address_2',
    'address_3',
    'phone',
    'cell_phone',
    'fax',
    'email',
    'housecall_distance',
    'housecall_additional_distance',
    'is_redeemed',
    'application_count',
    'note'
  ];

  protected $casts = [
    'birthday' => 'date',
    'is_redeemed' => 'boolean'
  ];
}