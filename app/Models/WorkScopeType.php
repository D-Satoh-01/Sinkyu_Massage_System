<?php
// app/Models/WorkScopeType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkScopeType extends Model
{
  use HasFactory;

  protected $connection = 'sinkyu_massage_system_db';
  protected $table = 'work_scope_types';

  protected $fillable = [
    'work_scope_type'
  ];
}
