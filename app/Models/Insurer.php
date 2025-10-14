<?php
// app/Models/Insurer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurer extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'insurers';

  protected $fillable = [
    'insurer_number',
    'insurer_name',
    'address',
    'recipient_name'
  ];
}
