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
        Schema::table('deposits', function (Blueprint $table) {
            $table->integer('clinic_user_id')->nullable()->after('id');
            $table->string('year_month', 7)->nullable()->after('clinic_user_id'); // YYYY-MM形式
            $table->text('treatment_dates')->nullable()->after('treatment_type'); // JSON形式で複数日付を格納
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn(['clinic_user_id', 'year_month', 'treatment_dates']);
        });
    }
};
