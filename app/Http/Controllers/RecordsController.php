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
      ->select('id', 'last_name', 'first_name', 'last_kana', 'first_kana')
      ->orderBy('last_kana', 'asc')
      ->orderBy('first_kana', 'asc')
      ->get();

    // 選択された利用者ID
    $selectedUserId = $request->input('clinic_user_id');

    // 実績データを取得
    $query = DB::table('records')
      ->leftJoin('clinic_users', 'records.clinic_user_id', '=', 'clinic_users.id')
      ->select(
        'records.*',
        'clinic_users.last_name',
        'clinic_users.first_name',
        'clinic_users.last_kana',
        'clinic_users.first_kana'
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
