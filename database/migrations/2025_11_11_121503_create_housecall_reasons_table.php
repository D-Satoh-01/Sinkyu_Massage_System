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
        Schema::connection('sinkyu_massage_system_db')->create('house_visit_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('house_visit_reason');
            $table->timestamps();
        });

        // 初期データ投入
        DB::connection('sinkyu_massage_system_db')->table('house_visit_reasons')->insert([
            ['house_visit_reason' => '－－－', 'created_at' => now(), 'updated_at' => now()],
            ['house_visit_reason' => '独歩による公共交通機関を使っての外出が困難', 'created_at' => now(), 'updated_at' => now()],
            ['house_visit_reason' => '認知症や視覚･内部･精神障害などにより単独での外出が困難', 'created_at' => now(), 'updated_at' => now()],
            ['house_visit_reason' => 'その他', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sinkyu_massage_system_db')->dropIfExists('house_visit_reasons');
    }
};
