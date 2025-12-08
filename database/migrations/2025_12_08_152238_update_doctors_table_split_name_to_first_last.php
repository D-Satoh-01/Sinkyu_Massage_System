<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('doctors', function (Blueprint $table) {
      // 新しいカラムを追加
      $table->string('last_name')->nullable()->after('updated_at');
      $table->string('first_name')->nullable()->after('last_name');
      $table->string('last_name_kana')->nullable()->after('first_name');
      $table->string('first_name_kana')->nullable()->after('last_name_kana');
    });

    // 既存のデータを移行（doctor_nameをlast_nameに、furiganaをlast_name_kanaにコピー）
    DB::statement('UPDATE doctors SET last_name = doctor_name, last_name_kana = furigana WHERE doctor_name IS NOT NULL');

    Schema::table('doctors', function (Blueprint $table) {
      // 古いカラムを削除
      $table->dropColumn(['doctor_name', 'furigana']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('doctors', function (Blueprint $table) {
      // 古いカラムを復元
      $table->string('doctor_name')->after('updated_at');
      $table->string('furigana')->nullable()->after('medical_institutions_id');
    });

    // データを戻す（last_nameをdoctor_nameに、last_name_kanaをfuriganaにコピー）
    DB::statement('UPDATE doctors SET doctor_name = CONCAT(COALESCE(last_name, ""), COALESCE(first_name, "")), furigana = CONCAT(COALESCE(last_name_kana, ""), COALESCE(first_name_kana, "")) WHERE last_name IS NOT NULL');

    Schema::table('doctors', function (Blueprint $table) {
      // 新しいカラムを削除
      $table->dropColumn(['last_name', 'first_name', 'last_name_kana', 'first_name_kana']);
    });
  }
};
