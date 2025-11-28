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
        Schema::rename('consents_massage-bodyparts', 'bodyparts-consents_massage');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('bodyparts-consents_massage', 'consents_massage-bodyparts');
    }
};
