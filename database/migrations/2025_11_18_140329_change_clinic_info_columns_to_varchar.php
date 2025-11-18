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
        Schema::table('clinic_info', function (Blueprint $table) {
            // bank_account_type_id を bank_account_type に変更（int → varchar）
            $table->string('bank_account_type', 50)->nullable()->after('closed_day_sunday');
            $table->dropColumn('bank_account_type_id');

            // health_center_registerd_location_id を health_center_registerd_location に変更（int → varchar）
            $table->string('health_center_registerd_location', 50)->nullable()->after('bank_account_number');
            $table->dropColumn('health_center_registerd_location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_info', function (Blueprint $table) {
            // ロールバック時は元に戻す
            $table->integer('bank_account_type_id')->nullable()->after('closed_day_sunday');
            $table->dropColumn('bank_account_type');

            $table->integer('health_center_registerd_location_id')->nullable()->after('bank_account_number');
            $table->dropColumn('health_center_registerd_location');
        });
    }
};
