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
        Schema::table('therapists', function (Blueprint $table) {
            // 新しいカラムを追加
            $table->string('last_name')->nullable()->after('updated_at');
            $table->string('first_name')->nullable()->after('last_name');
            $table->string('last_name_kana')->nullable()->after('first_name');
            $table->string('first_name_kana')->nullable()->after('last_name_kana');
        });

        // 既存データを新フィールドに移行（therapist_name → last_name, furigana → last_name_kana）
        DB::table('therapists')->get()->each(function ($therapist) {
            DB::table('therapists')
                ->where('id', $therapist->id)
                ->update([
                    'last_name' => $therapist->therapist_name,
                    'last_name_kana' => $therapist->furigana,
                    'first_name' => '',
                    'first_name_kana' => ''
                ]);
        });

        Schema::table('therapists', function (Blueprint $table) {
            // 旧カラムを削除
            $table->dropColumn(['therapist_name', 'furigana']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('therapists', function (Blueprint $table) {
            // 旧カラムを復元
            $table->string('therapist_name')->nullable()->after('updated_at');
            $table->string('furigana')->nullable()->after('therapist_name');
        });

        // データを旧フィールドに戻す
        DB::table('therapists')->get()->each(function ($therapist) {
            DB::table('therapists')
                ->where('id', $therapist->id)
                ->update([
                    'therapist_name' => $therapist->last_name . $therapist->first_name,
                    'furigana' => $therapist->last_name_kana . $therapist->first_name_kana
                ]);
        });

        Schema::table('therapists', function (Blueprint $table) {
            // 新カラムを削除
            $table->dropColumn(['last_name', 'first_name', 'last_name_kana', 'first_name_kana']);
        });
    }
};
