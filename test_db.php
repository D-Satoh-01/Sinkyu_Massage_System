<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== データベース接続・操作方法テスト ===\n\n";

// ========================================
// 1. テーブル一覧取得
// ========================================
echo "【1. テーブル一覧取得方法】\n";

// 方法1: SHOW TABLES
try {
    $tables = DB::select('SHOW TABLES');
    echo "✓ 方法1 (SHOW TABLES): " . count($tables) . "個\n";
} catch (Exception $e) {
    echo "✗ 方法1: 失敗 - " . $e->getMessage() . "\n";
}

// 方法2: information_schema
try {
    $tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()');
    echo "✓ 方法2 (information_schema): " . count($tables) . "個\n";
} catch (Exception $e) {
    echo "✗ 方法2: 失敗 - " . $e->getMessage() . "\n";
}

// 方法3: DoctrineSchemaManager (Laravel 11で廃止)
try {
    $sm = DB::connection()->getDoctrineSchemaManager();
    $tables = $sm->listTableNames();
    echo "✓ 方法3 (DoctrineSchemaManager): " . count($tables) . "個\n";
} catch (Exception $e) {
    echo "✗ 方法3 (DoctrineSchemaManager): 失敗 - " . get_class($e) . "\n";
}

// 方法4: SchemaBuilder::getTables()
try {
    $tables = DB::connection()->getSchemaBuilder()->getTables();
    echo "✓ 方法4 (SchemaBuilder::getTables): " . count($tables) . "個 (システムテーブル含む)\n";
} catch (Exception $e) {
    echo "✗ 方法4: 失敗 - " . get_class($e) . "\n";
}

// 方法5: SchemaBuilder::getTableListing()
try {
    $tables = DB::connection()->getSchemaBuilder()->getTableListing();
    echo "✓ 方法5 (SchemaBuilder::getTableListing): " . count($tables) . "個 (システムテーブル含む)\n";
} catch (Exception $e) {
    echo "✗ 方法5: 失敗 - " . get_class($e) . "\n";
}

echo "\n";

// ========================================
// 2. テーブル存在確認
// ========================================
echo "【2. テーブル存在確認】\n";

$testTable = 'clinic_users';

// 方法1: SchemaBuilder::hasTable()
try {
    $exists = DB::connection()->getSchemaBuilder()->hasTable($testTable);
    echo "✓ 方法1 (hasTable): " . ($exists ? '存在する' : '存在しない') . "\n";
} catch (Exception $e) {
    echo "✗ 方法1: 失敗\n";
}

// 方法2: SHOW TABLES LIKE
try {
    $result = DB::select("SHOW TABLES LIKE '$testTable'");
    echo "✓ 方法2 (SHOW TABLES LIKE): " . (count($result) > 0 ? '存在する' : '存在しない') . "\n";
} catch (Exception $e) {
    echo "✗ 方法2: 失敗\n";
}

echo "\n";

// ========================================
// 3. カラム情報取得
// ========================================
echo "【3. カラム情報取得】\n";

// 方法1: DESCRIBE
try {
    $columns = DB::select("DESCRIBE $testTable");
    echo "✓ 方法1 (DESCRIBE): " . count($columns) . "カラム\n";
} catch (Exception $e) {
    echo "✗ 方法1: 失敗\n";
}

// 方法2: SHOW COLUMNS
try {
    $columns = DB::select("SHOW COLUMNS FROM $testTable");
    echo "✓ 方法2 (SHOW COLUMNS): " . count($columns) . "カラム\n";
} catch (Exception $e) {
    echo "✗ 方法2: 失敗\n";
}

// 方法3: information_schema
try {
    $columns = DB::select("SELECT column_name FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '$testTable'");
    echo "✓ 方法3 (information_schema): " . count($columns) . "カラム\n";
} catch (Exception $e) {
    echo "✗ 方法3: 失敗\n";
}

// 方法4: SchemaBuilder::getColumnListing()
try {
    $columns = DB::connection()->getSchemaBuilder()->getColumnListing($testTable);
    echo "✓ 方法4 (getColumnListing): " . count($columns) . "カラム\n";
} catch (Exception $e) {
    echo "✗ 方法4: 失敗\n";
}

// 方法5: SchemaBuilder::getColumns()
try {
    $columns = DB::connection()->getSchemaBuilder()->getColumns($testTable);
    echo "✓ 方法5 (getColumns): " . count($columns) . "カラム (詳細情報付き)\n";
} catch (Exception $e) {
    echo "✗ 方法5: 失敗\n";
}

echo "\n";

// ========================================
// 4. データ取得
// ========================================
echo "【4. データ取得】\n";

// 方法1: DB::select()
try {
    $users = DB::select("SELECT * FROM $testTable LIMIT 1");
    echo "✓ 方法1 (DB::select): " . count($users) . "件取得\n";
} catch (Exception $e) {
    echo "✗ 方法1: 失敗\n";
}

// 方法2: DB::table()->get()
try {
    $users = DB::table($testTable)->limit(1)->get();
    echo "✓ 方法2 (DB::table()->get): " . count($users) . "件取得\n";
} catch (Exception $e) {
    echo "✗ 方法2: 失敗\n";
}

// 方法3: DB::table()->first()
try {
    $user = DB::table($testTable)->first();
    echo "✓ 方法3 (DB::table()->first): " . ($user ? '1件取得' : 'データなし') . "\n";
} catch (Exception $e) {
    echo "✗ 方法3: 失敗\n";
}

// 方法4: Model経由 (ClinicUserモデルが存在する場合)
try {
    $user = \App\Models\ClinicUser::first();
    echo "✓ 方法4 (Model::first): " . ($user ? '1件取得' : 'データなし') . "\n";
} catch (Throwable $e) {
    echo "✗ 方法4: 失敗 - モデルが存在しないか、エラー発生\n";
}

echo "\n";

// ========================================
// 5. レコード数取得
// ========================================
echo "【5. レコード数取得】\n";

// 方法1: DB::table()->count()
try {
    $count = DB::table($testTable)->count();
    echo "✓ 方法1 (DB::table()->count): {$count}件\n";
} catch (Exception $e) {
    echo "✗ 方法1: 失敗\n";
}

// 方法2: SELECT COUNT(*)
try {
    $result = DB::select("SELECT COUNT(*) as count FROM $testTable");
    echo "✓ 方法2 (SELECT COUNT): {$result[0]->count}件\n";
} catch (Exception $e) {
    echo "✗ 方法2: 失敗\n";
}

// 方法3: Model::count()
try {
    $count = \App\Models\ClinicUser::count();
    echo "✓ 方法3 (Model::count): {$count}件\n";
} catch (Throwable $e) {
    echo "✗ 方法3: 失敗\n";
}

echo "\n";

// ========================================
// 6. データベース基本情報
// ========================================
echo "【6. データベース基本情報】\n";

// データベース名
try {
    $dbName = DB::connection()->getDatabaseName();
    echo "✓ データベース名: $dbName\n";
} catch (Exception $e) {
    echo "✗ データベース名取得: 失敗\n";
}

// ドライバ名
try {
    $driver = DB::connection()->getDriverName();
    echo "✓ ドライバ名: $driver\n";
} catch (Exception $e) {
    echo "✗ ドライバ名取得: 失敗\n";
}

// 接続確認
try {
    $pdo = DB::connection()->getPdo();
    echo "✓ DB接続: " . ($pdo ? '成功' : '失敗') . "\n";
} catch (Exception $e) {
    echo "✗ DB接続: 失敗\n";
}

echo "\n完了\n";
