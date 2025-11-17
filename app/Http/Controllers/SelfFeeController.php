<?php
//-- app/Http/Controllers/SelfFeeController.php --//

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelfFeeController extends Controller
{
  /**
   * 自費施術料金のインデックスページを表示
   */
  public function index()
  {
    $items = DB::table('self_pay_fees')->orderBy('id')->get();
    return view('master.self-fees.self-fees_index', compact('items'));
  }

  /**
   * 自費施術料金を更新
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'treatment_name' => 'required|string|max:255',
      'fee_amount' => 'required|numeric|min:0',
    ]);

    DB::table('self_pay_fees')
      ->where('id', $id)
      ->update([
        'treatment_name' => $request->treatment_name,
        'fee_amount' => $request->fee_amount,
      ]);

    return redirect()->route('master.self-fees.index')->with('success', '更新完了');
  }

  /**
   * 自費施術料金を新規登録
   */
  public function store(Request $request)
  {
    $request->validate([
      'treatment_name' => 'required|string|max:255',
      'fee_amount' => 'required|numeric|min:0',
    ]);

    DB::table('self_pay_fees')->insert([
      'treatment_name' => $request->treatment_name,
      'fee_amount' => $request->fee_amount,
    ]);

    return redirect()->route('master.self-fees.index')->with('success', '登録完了');
  }

  /**
   * 自費施術料金を削除
   */
  public function destroy($id)
  {
    DB::table('self_pay_fees')->where('id', $id)->delete();
    return redirect()->route('master.self-fees.index')->with('success', '削除完了');
  }
}
