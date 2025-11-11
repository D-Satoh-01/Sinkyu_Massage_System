<?php
// app/Models/HouseVisitReason.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseVisitReason extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'house_visit_reasons';

  protected $fillable = [
    'house_visit_reason'
  ];
}
