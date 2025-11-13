<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // 1つ目のテーブル名変更
    DB::statement('RENAME TABLE `consents_massage－bodyparts` TO `consents_massage-bodyparts`');
    echo "✓ テーブル名を変更しました: consents_massage－bodyparts → consents_massage-bodyparts\n";
} catch (Exception $e) {
    echo "✗ エラー (consents_massage－bodyparts): " . $e->getMessage() . "\n";
}

try {
    // 2つ目のテーブル名変更
    DB::statement('RENAME TABLE `records−bodyparts` TO `records-bodyparts`');
    echo "✓ テーブル名を変更しました: records−bodyparts → records-bodyparts\n";
} catch (Exception $e) {
    echo "✗ エラー (records−bodyparts): " . $e->getMessage() . "\n";
}

echo "\n完了\n";
