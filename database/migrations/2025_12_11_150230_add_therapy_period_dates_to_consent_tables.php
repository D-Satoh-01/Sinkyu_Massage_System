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
        // consents_massage テーブルに therapy_period_start_date と therapy_period_end_date を追加
        Schema::table('consents_massage', function (Blueprint $table) {
            $table->date('therapy_period_start_date')->nullable()->after('therapy_period');
            $table->date('therapy_period_end_date')->nullable()->after('therapy_period_start_date');
        });

        // consents_acupuncture テーブルに therapy_period_start_date と therapy_period_end_date を追加
        Schema::table('consents_acupuncture', function (Blueprint $table) {
            $table->date('therapy_period_start_date')->nullable()->after('therapy_period');
            $table->date('therapy_period_end_date')->nullable()->after('therapy_period_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // consents_massage テーブルから therapy_period_start_date と therapy_period_end_date を削除
        Schema::table('consents_massage', function (Blueprint $table) {
            $table->dropColumn(['therapy_period_start_date', 'therapy_period_end_date']);
        });

        // consents_acupuncture テーブルから therapy_period_start_date と therapy_period_end_date を削除
        Schema::table('consents_acupuncture', function (Blueprint $table) {
            $table->dropColumn(['therapy_period_start_date', 'therapy_period_end_date']);
        });
    }
};
