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
        Schema::table('records', function (Blueprint $table) {
            $table->integer('clinic_user_id')->nullable()->after('updated_at');
            $table->foreign('clinic_user_id')->references('id')->on('clinic_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropForeign(['clinic_user_id']);
            $table->dropColumn('clinic_user_id');
        });
    }
};
