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
        if (Schema::hasTable('concenting_doctor_history_massage')) {
            Schema::rename('concenting_doctor_history_massage', 'consenting_doctor_history_massage');
        }
        if (Schema::hasTable('concenting_doctor_history_acupuncture')) {
            Schema::rename('concenting_doctor_history_acupuncture', 'consenting_doctor_history_acupuncture');
        }
        if (Schema::hasTable('concenting_doctor_history_massage_bodyparts')) {
            Schema::rename('concenting_doctor_history_massage_bodyparts', 'consenting_doctor_history_massage_bodyparts');
        }
        // plans_info は変更なし
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('consenting_doctor_history_massage')) {
            Schema::rename('consenting_doctor_history_massage', 'concenting_doctor_history_massage');
        }
        if (Schema::hasTable('consenting_doctor_history_acupuncture')) {
            Schema::rename('consenting_doctor_history_acupuncture', 'concenting_doctor_history_acupuncture');
        }
        if (Schema::hasTable('consenting_doctor_history_massage_bodyparts')) {
            Schema::rename('consenting_doctor_history_massage_bodyparts', 'concenting_doctor_history_massage_bodyparts');
        }
    }
};
