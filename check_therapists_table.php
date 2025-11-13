<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'DB: ' . DB::connection()->getDatabaseName() . PHP_EOL;

$tables = DB::select('SHOW TABLES');
foreach($tables as $table) {
  $tableName = array_values((array)$table)[0];
  if($tableName === 'therapists') {
    $columns = DB::select('DESCRIBE therapists');
    echo 'therapists table columns:' . PHP_EOL;
    foreach($columns as $col) {
      echo '- ' . $col->Field . ' (' . $col->Type . ')' . PHP_EOL;
    }
  }
}
