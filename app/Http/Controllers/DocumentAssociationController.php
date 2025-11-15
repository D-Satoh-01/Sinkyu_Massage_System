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
    $documents = DB::table('standard_documents')->orderBy('id')->get();
    return view('master.document-association.document-association_index', compact('documents'));
  }

  /**
   * 標準文書を関連付け
   */
  public function associate(Request $request, $id)
  {
    $request->validate([
      'associated_type' => 'required|string|max:255',
      'associated_id' => 'required|integer',
    ]);

    DB::table('standard_documents')
      ->where('id', $id)
      ->update([
        'associated_type' => $request->associated_type,
        'associated_id' => $request->associated_id,
      ]);

    return redirect()->route('master.document-association.index')->with('success', '関連付け完了');
  }
}
