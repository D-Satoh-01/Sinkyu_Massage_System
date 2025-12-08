<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('caremanagers', function (Blueprint $table) {
            // 新しいカラムを追加
            $table->string('last_name')->nullable()->after('updated_at');
            $table->string('first_name')->nullable()->after('last_name');
            $table->string('last_name_kana')->nullable()->after('first_name');
            $table->string('first_name_kana')->nullable()->after('last_name_kana');
        });

        // 既存データを新フィールドに移行（caremanager_name → last_name, furigana → last_name_kana）
        DB::table('caremanagers')->get()->each(function ($caremanager) {
            DB::table('caremanagers')
                ->where('id', $caremanager->id)
                ->update([
                    'last_name' => $caremanager->caremanager_name,
                    'last_name_kana' => $caremanager->furigana,
                    'first_name' => '',
                    'first_name_kana' => ''
                ]);
        });

        Schema::table('caremanagers', function (Blueprint $table) {
            // 旧カラムを削除
            $table->dropColumn(['caremanager_name', 'furigana']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('caremanagers', function (Blueprint $table) {
            // 旧カラムを復元
            $table->string('caremanager_name')->nullable()->after('updated_at');
            $table->string('furigana')->nullable()->after('caremanager_name');
        });

        // データを旧フィールドに戻す
        DB::table('caremanagers')->get()->each(function ($caremanager) {
            DB::table('caremanagers')
                ->where('id', $caremanager->id)
                ->update([
                    'caremanager_name' => $caremanager->last_name . $caremanager->first_name,
                    'furigana' => $caremanager->last_name_kana . $caremanager->first_name_kana
                ]);
        });

        Schema::table('caremanagers', function (Blueprint $table) {
            // 新カラムを削除
            $table->dropColumn(['last_name', 'first_name', 'last_name_kana', 'first_name_kana']);
        });
    }
};
