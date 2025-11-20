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
      Schema::table('document_association', function (Blueprint $table) {
        // 既存の外部キー制約を削除
        $table->dropForeign('document_association_document_titles_FK');
        $table->dropForeign('document_association_document_titles_FK_1');

        // 新しい外部キー制約を追加 (documentsテーブルを参照)
        $table->foreign('document_name_id_1')
          ->references('id')->on('documents')
          ->onDelete('cascade');

        $table->foreign('document_name_id_2')
          ->references('id')->on('documents')
          ->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('document_association', function (Blueprint $table) {
        // 新しい外部キー制約を削除
        $table->dropForeign(['document_name_id_1']);
        $table->dropForeign(['document_name_id_2']);

        // 元の外部キー制約を復元
        $table->foreign('document_name_id_1')
          ->references('id')->on('document_templates')
          ->onDelete('cascade');

        $table->foreign('document_name_id_2')
          ->references('id')->on('document_templates')
          ->onDelete('cascade');
      });
    }
};
