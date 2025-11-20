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
        // 外部キー制約を削除（制約名を直接指定）
        $table->dropForeign('document_association_document_name_id_1_foreign');
        $table->dropForeign('document_association_document_name_id_2_foreign');

        // カラム名を変更
        $table->renameColumn('document_id_1', 'document_name_id_1');
        $table->renameColumn('document_id_2', 'document_name_id_2');
      });

      // カラム名変更後に外部キー制約を再追加
      Schema::table('document_association', function (Blueprint $table) {
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
        // 外部キー制約を削除
        $table->dropForeign(['document_name_id_1']);
        $table->dropForeign(['document_name_id_2']);

        // カラム名を元に戻す
        $table->renameColumn('document_name_id_1', 'document_id_1');
        $table->renameColumn('document_name_id_2', 'document_id_2');
      });

      // カラム名変更後に外部キー制約を再追加
      Schema::table('document_association', function (Blueprint $table) {
        $table->foreign('document_id_1')
          ->references('id')->on('documents')
          ->onDelete('cascade');

        $table->foreign('document_id_2')
          ->references('id')->on('documents')
          ->onDelete('cascade');
      });
    }
};
