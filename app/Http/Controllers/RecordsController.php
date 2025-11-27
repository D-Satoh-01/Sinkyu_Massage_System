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
    // clinic_infoテーブルから定休日情報を取得
    $clinicInfo = DB::table('clinic_info')->first();
    $closedDays = [
      'monday' => $clinicInfo->closed_day_monday ?? 0,
      'tuesday' => $clinicInfo->closed_day_tuesday ?? 0,
      'wednesday' => $clinicInfo->closed_day_wednesday ?? 0,
      'thursday' => $clinicInfo->closed_day_thursday ?? 0,
      'friday' => $clinicInfo->closed_day_friday ?? 0,
      'saturday' => $clinicInfo->closed_day_saturday ?? 0,
      'sunday' => $clinicInfo->closed_day_sunday ?? 0,
    ];

    // 利用者リストを取得
    $clinicUsers = DB::table('clinic_users')
      ->select('id', 'last_name', 'first_name', 'last_kana', 'first_kana')
      ->orderBy('last_kana')
      ->orderBy('first_kana')
      ->get();

    // 選択された利用者ID
    $selectedUserId = $request->input('clinic_user_id');

    return view('records.records_index', [
      'closedDays' => $closedDays,
      'clinicUsers' => $clinicUsers,
      'selectedUserId' => $selectedUserId,
      'page_header_title' => '実績データ',
    ]);
  }
}
