<?php
//-- app/Http/Controllers/DocumentAssociationController.php --//

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentAssociationController extends Controller
{
  /**
   * 標準文書の確認および関連付けのインデックスページを表示
   */
  public function index()
  {
    // document_templatesからカテゴリ一覧を取得
    $categories = DB::table('document_templates')->orderBy('id')->get();

    // documentsテーブルから全文書を取得
    $documents = DB::table('documents')->orderBy('id')->get();

    // document_associationから既存の関連付けを取得
    $associations = DB::table('document_association')->get()->keyBy('document_id_1');

    return view('master.document-association.document-association_index', [
      'categories' => $categories,
      'documents' => $documents,
      'associations' => $associations,
      'page_header_title' => '標準文書の確認および関連付け'
    ]);
  }

  /**
   * 標準文書を関連付け
   */
  public function associate(Request $request, $id)
  {
    $request->validate([
      'document_id_2' => 'nullable|integer',
    ]);

    // 既存の関連付けがあるかチェック
    $existing = DB::table('document_association')
      ->where('document_id_1', $id)
      ->first();

    $now = now();

    if ($existing) {
      // 更新
      DB::table('document_association')
        ->where('document_id_1', $id)
        ->update([
          'document_id_2' => $request->document_id_2,
          'updated_at' => $now,
        ]);
    } else {
      // 新規作成
      DB::table('document_association')->insert([
        'document_id_1' => $id,
        'document_id_2' => $request->document_id_2,
        'created_at' => $now,
        'updated_at' => $now,
      ]);
    }

    return redirect()->route('master.document-association.index')->with('success', '関連付け完了');
  }
}
