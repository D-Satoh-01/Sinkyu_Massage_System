<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$fullwidth_digits = ['０', '１', '２', '３', '４', '５', '６', '７', '８', '９'];

$tables = DB::connection('sinkyu_massage_system_db')->select('SHOW TABLES');
$table_names = array_map(function($table) {
    return array_values((array)$table)[0];
}, $tables);

$total_count = 0;

foreach ($table_names as $table_name) {
    $columns = DB::connection('sinkyu_massage_system_db')->select("DESCRIBE `$table_name`");
    $column_names = array_map(function($column) {
        return $column->Field;
    }, $columns);

    $count = 0;
    foreach ($column_names as $column) {
        foreach ($fullwidth_digits as $digit) {
            $count += DB::connection('sinkyu_massage_system_db')->table($table_name)->where($column, 'like', '%' . $digit . '%')->count();
        }
    }

    if ($count > 0) {
        echo "Table: $table_name - Records with full-width digits: $count\n";
        $total_count += $count;
    }
}

echo "Total records with full-width digits across all tables: " . $total_count . "\n";
