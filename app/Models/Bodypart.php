<?php
// app/Models/Bodypart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodypart extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'bodyparts';

  protected $fillable = ['bodypart'];

  public function consentsMassage()
  {
    return $this->belongsToMany(
      ConsentMassage::class,
      'consents_massage_bodyparts',
      'symtom_1_bodyparts_id',
      'consenting_doctor_history_massage_id'
    );
  }
}
