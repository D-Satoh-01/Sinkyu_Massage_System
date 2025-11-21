<?php
//-- app/Http/Controllers/RecordsController.php --//

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 実績データ管理コントローラー
 *
 * 利用者の施術実績データの管理を担当する。
 * - 実績データの一覧表示
 * - 利用者選択による絞り込み
 */
class RecordsController extends Controller
{
  /**
   * 実績データ一覧を表示
   *
   * @param Request $request
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    // 利用者一覧を取得（プルダウンメニュー用）
    $clinicUsers = DB::table('clinic_users')
      ->select('id', 'clinic_user_name', 'furigana')
      ->orderBy('furigana', 'asc')
      ->get();

    // 選択された利用者ID
    $selectedUserId = $request->input('clinic_user_id');

    // 実績データを取得
    $query = DB::table('records')
      ->leftJoin('clinic_users', 'records.clinic_user_id', '=', 'clinic_users.id')
      ->select(
        'records.*',
        'clinic_users.clinic_user_name',
        'clinic_users.furigana'
      )
      ->orderBy('records.date', 'desc')
      ->orderBy('records.start_time', 'desc');

    // 利用者IDで絞り込み
    if ($selectedUserId) {
      $query->where('records.clinic_user_id', $selectedUserId);
    }

    $records = $query->get();

    return view('records.records_index', compact('clinicUsers', 'records', 'selectedUserId'));
  }
}
