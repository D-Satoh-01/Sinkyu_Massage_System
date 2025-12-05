<?php
//-- app/Http/Controllers/ScheduleController.php --//

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

/**
 * スケジュール管理コントローラー
 *
 * 施術者のスケジュール表示を担当する。
 * - スケジュール一覧表示（週表示・月表示）
 * - 施術者による絞り込み
 * - recordsテーブルからの施術データ取得
 */
class ScheduleController extends Controller
{
  /**
   * スケジュール画面を表示
   *
   * @param Request $request
   * @return \Illuminate\View\View
   */
  public function index(Request $request)
  {
    // 施術者リストを取得
    $therapists = DB::table('therapists')
      ->select('id', 'therapist_name', 'furigana')
      ->orderBy('furigana')
      ->get();

    // 選択された施術者ID
    $selectedTherapistId = $request->input('therapist_id');

    // 営業時間を取得
    $clinicInfo = DB::table('clinic_info')->first();
    $businessHoursStart = $clinicInfo->business_hours_start ?? '09:00:00';
    $businessHoursEnd = $clinicInfo->business_hours_end ?? '18:00:00';

    return view('schedules.schedules_index', [
      'therapists' => $therapists,
      'selectedTherapistId' => $selectedTherapistId,
      'businessHoursStart' => $businessHoursStart,
      'businessHoursEnd' => $businessHoursEnd,
      'page_header_title' => 'スケジュール',
    ]);
  }

  /**
   * スケジュールデータをJSON形式で取得
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getData(Request $request)
  {
    $therapistId = $request->input('therapist_id');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $query = DB::table('records')
      ->join('clinic_users', 'records.clinic_user_id', '=', 'clinic_users.id')
      ->select(
        'records.id',
        'records.date',
        'records.start_time',
        'records.end_time',
        'records.therapy_type',
        DB::raw('CONCAT(clinic_users.last_name, " ", clinic_users.first_name) as user_name')
      )
      ->whereBetween('records.date', [$startDate, $endDate]);

    if ($therapistId) {
      $query->where('records.therapist_id', $therapistId);
    }

    $records = $query->get();

    return response()->json($records);
  }
}
