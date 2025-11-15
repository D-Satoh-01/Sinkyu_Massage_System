<?php
//-- app/Http/Controllers/DocumentController.php --//

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
  /**
   * 文面編集のインデックスページを表示
   */
  public function index()
  {
    $items = DB::table('text_templates')->orderBy('id')->get();
    return view('master.documents.documents_index', compact('items'));
  }

  /**
   * 文面を更新
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'template_name' => 'required|string|max:255',
      'template_content' => 'required|string',
    ]);

    DB::table('text_templates')
      ->where('id', $id)
      ->update([
        'template_name' => $request->template_name,
        'template_content' => $request->template_content,
      ]);

    return redirect()->route('master.documents.index')->with('success', '更新完了');
  }

  /**
   * 文面を新規登録
   */
  public function store(Request $request)
  {
    $request->validate([
      'template_name' => 'required|string|max:255',
      'template_content' => 'required|string',
    ]);

    DB::table('text_templates')->insert([
      'template_name' => $request->template_name,
      'template_content' => $request->template_content,
    ]);

    return redirect()->route('master.documents.index')->with('success', '登録完了');
  }

  /**
   * 文面を削除
   */
  public function destroy($id)
  {
    DB::table('text_templates')->where('id', $id)->delete();
    return redirect()->route('master.documents.index')->with('success', '削除完了');
  }
}
