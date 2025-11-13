<?php
// app/Models/AssistanceLevel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistanceLevel extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'assistance_levels';

  protected $fillable = [
    'assistance_level'
  ];
}
