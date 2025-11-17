<?php
//-- app/Http/Controllers/DocumentController.php --//

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
  /**
   * 文書のインデックスページを表示
   */
  public function index()
  {
    $items = DB::table('documents')->orderBy('id')->get();
    return view('master.documents.documents_index', compact('items'));
  }

  /**
   * 新規登録フォームを表示
   */
  public function create()
  {
    // document_templatesテーブルからカテゴリ一覧を取得
    $categories = DB::table('document_templates')
      ->select('category')
      ->distinct()
      ->whereNotNull('category')
      ->orderBy('category')
      ->pluck('category');

    // document_templatesテーブルからテンプレート一覧を取得
    $templates = DB::table('document_templates')
      ->select('id', 'category', 'name')
      ->orderBy('category')
      ->orderBy('name')
      ->get();

    return view('master.documents.documents_registration', compact('categories', 'templates'));
  }

  /**
   * 文書を更新
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'document_category_id' => 'required|integer',
      'document_template_id' => 'nullable|integer',
      'document_content' => 'required|string|max:255',
      'font_size' => 'nullable|integer',
      'line_height' => 'nullable|integer',
    ]);

    DB::table('documents')
      ->where('id', $id)
      ->update([
        'document_category_id' => $request->document_category_id,
        'document_template_id' => $request->document_template_id,
        'document_content' => $request->document_content,
        'font_size' => $request->font_size,
        'line_height' => $request->line_height,
      ]);

    return redirect()->route('master.documents.index')->with('success', '更新完了');
  }

  /**
   * 文書を新規登録
   */
  public function store(Request $request)
  {
    $request->validate([
      'category' => 'required|string|max:255',
      'name' => 'nullable|string|max:255',
      'new_name' => 'nullable|string|max:255',
      'content' => 'required|string|max:255',
      'font_size' => 'nullable|integer',
      'line_height' => 'nullable|integer',
    ]);

    // 新規文書名称が入力されていればそれを使用、なければ既存の文書名称を使用
    $documentName = !empty($request->new_name) ? $request->new_name : $request->name;

    DB::table('documents')->insert([
      'category' => $request->category,
      'name' => $documentName,
      'content' => $request->content,
      'font_size' => $request->font_size ?? 12,
      'line_height' => $request->line_height ?? 7,
    ]);

    return redirect()->route('master.documents.index')->with('success', '登録完了');
  }

  /**
   * 文書を削除
   */
  public function destroy($id)
  {
    DB::table('documents')->where('id', $id)->delete();
    return redirect()->route('master.documents.index')->with('success', '削除完了');
  }

  /**
   * 文書のプレビューを表示
   */
  public function preview($id)
  {
    $document = DB::table('documents')->where('id', $id)->first();

    if (!$document) {
      abort(404, '文書が見つかりません');
    }

    // clinic_infoテーブルから事業所情報を取得
    $clinicInfo = DB::table('clinic_info')->first();

    // documentsテーブルのnameカラムからdocument_templatesテーブルのIDを取得
    $template = DB::table('document_templates')
      ->where('id', $document->name)
      ->first();

    if (!$template || !$template->view_name) {
      abort(404, 'テンプレートが見つかりません');
    }

    // テンプレートのビュー名を使用してビューを返す
    return view($template->view_name, compact('document', 'clinicInfo'));
  }
}
