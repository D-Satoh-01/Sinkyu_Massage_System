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
      'clinic_user_name'
  ];
}
