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
        Schema::table('bodyparts-consents_massage', function (Blueprint $table) {
            $table->renameColumn('consenting_doctor_history_massage_id', 'consents_massage_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bodyparts-consents_massage', function (Blueprint $table) {
            $table->renameColumn('consents_massage_id', 'consenting_doctor_history_massage_id');
        });
    }
};
