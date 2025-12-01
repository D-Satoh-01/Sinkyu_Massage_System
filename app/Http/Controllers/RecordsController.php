<?php
//-- app/Http/Controllers/RecordsController.php --//

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RecordRequest;

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

    // 利用者が選択されている場合、関連データを取得
    $insurances = null;
    $latestInsuranceId = null;
    $consentsAcupuncture = null;
    $consentsMassage = null;
    $hasRecentRecords = false;
    $records = collect();
    $selectedYear = null;
    $selectedMonth = null;

    if ($selectedUserId) {
      // 保険情報を取得（有効期限の降順）
      $insurances = DB::table('insurances')
        ->leftJoin('insurers', 'insurances.insurers_id', '=', 'insurers.id')
        ->where('insurances.clinic_user_id', $selectedUserId)
        ->select('insurances.*', 'insurers.insurer_number')
        ->orderBy('insurances.expiry_date', 'desc')
        ->get();

      // 最新の保険IDを取得（有効期限の降順で最初のレコード）
      if ($insurances && $insurances->count() > 0) {
        $latestInsuranceId = $insurances->first()->id;
      }

      // 同意書情報（はり・きゅう）を取得（同意終了日の降順）
      $consentsAcupuncture = DB::table('consents_acupuncture')
        ->where('clinic_user_id', $selectedUserId)
        ->orderBy('consenting_end_date', 'desc')
        ->first();

      // 同意書情報（あんま・マッサージ）を取得（同意終了日の降順）
      $consentsMassage = DB::table('consents_massage')
        ->where('clinic_user_id', $selectedUserId)
        ->orderBy('consenting_end_date', 'desc')
        ->first();

      // 1ヶ月以内の実績データの有無をチェック
      $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
      $hasRecentRecords = DB::table('records')
        ->where('clinic_user_id', $selectedUserId)
        ->where('date', '>=', $oneMonthAgo)
        ->exists();

      // 選択された年月を取得（デフォルトは現在の年月）
      $selectedYear = $request->input('year', date('Y'));
      $selectedMonth = $request->input('month', date('m'));

      // 選択された年月の実績データを取得
      $startDate = sprintf('%04d-%02d-01', $selectedYear, $selectedMonth);
      $endDate = date('Y-m-t', strtotime($startDate));

      $records = DB::table('records')
        ->leftJoin('therapy_contents', 'records.therapy_conetnt_id', '=', 'therapy_contents.id')
        ->leftJoin('therapists', 'records.therapist_id', '=', 'therapists.id')
        ->where('records.clinic_user_id', $selectedUserId)
        ->whereBetween('records.date', [$startDate, $endDate])
        ->select(
          'records.*',
          'therapy_contents.therapy_content',
          'therapists.therapist_name'
        )
        ->orderBy('records.date')
        ->orderBy('records.start_time')
        ->get()
        ->groupBy(function($record) {
          // 施術内容、施術者、時刻が同じレコードをグループ化
          return sprintf(
            '%s_%s_%s_%s',
            $record->therapy_conetnt_id,
            $record->therapist_id,
            $record->start_time,
            $record->end_time
          );
        })
        ->map(function($group) {
          // グループの最初のレコードを代表として使用
          $representative = $group->first();
          // 施術日の配列を追加
          $representative->dates = $group->pluck('date')->toArray();
          return $representative;
        })
        ->values();
    }

    // 施術者リストを取得
    $therapists = DB::table('therapists')
      ->select('id', 'therapist_name', 'furigana')
      ->orderBy('furigana')
      ->get();

    // 施術内容リストを取得
    $therapyContents = DB::table('therapy_contents')
      ->select('id', 'therapy_content')
      ->orderBy('id')
      ->get();

    // 請求区分リストを取得
    $billCategories = DB::table('bill_categories')
      ->select('id', 'bill_category')
      ->get();

    return view('records.records_index', [
      'closedDays' => $closedDays,
      'clinicUsers' => $clinicUsers,
      'selectedUserId' => $selectedUserId,
      'insurances' => $insurances,
      'latestInsuranceId' => $latestInsuranceId,
      'consentsAcupuncture' => $consentsAcupuncture,
      'consentsMassage' => $consentsMassage,
      'hasRecentRecords' => $hasRecentRecords,
      'therapists' => $therapists,
      'therapyContents' => $therapyContents,
      'billCategories' => $billCategories,
      'records' => $records,
      'selectedYear' => $selectedYear,
      'selectedMonth' => $selectedMonth,
      'page_header_title' => '実績データ',
    ]);
  }

  /**
   * 実績データを登録
   *
   * @param RecordRequest $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(RecordRequest $request)
  {
    $validated = $request->validated();

    try {
      DB::beginTransaction();

      // 往療距離の配列を取得
      $housecallDistances = $request->input('housecall_distance', []);

      // 選択された日付ごとにレコードを作成
      foreach ($housecallDistances as $date => $distance) {
        // recordsテーブルにデータを挿入
        $recordId = DB::table('records')->insertGetId([
          'clinic_user_id' => $validated['clinic_user_id'],
          'date' => $date,
          'start_time' => $validated['start_time'],
          'end_time' => $validated['end_time'],
          'therapy_type' => $validated['therapy_type'],
          'therapy_category' => $validated['therapy_category'],
          'insurance_category' => $validated['insurance_category'] ?? null,
          'housecall_distance' => $validated['therapy_category'] == 2 ? $distance : null,
          'therapy_days' => count($housecallDistances),
          'consent_expiry' => $validated['consent_expiry'] ?? null,
          'therapy_conetnt_id' => $validated['therapy_content_id'],
          'bill_category_id' => $validated['bill_category_id'],
          'therapist_id' => $validated['therapist_id'],
          'abstract' => $validated['abstract'] ?? null,
          'created_at' => now(),
          'updated_at' => now(),
        ]);

        // あんま･マッサージの場合、bodyparts-recordsテーブルに身体部位を保存
        if ($validated['therapy_type'] == 2 && isset($validated['bodyparts'])) {
          foreach ($validated['bodyparts'] as $bodypartId) {
            DB::table('bodyparts-records')->insert([
              'records_id' => $recordId,
              'therapy_type_bodyparts_id' => $bodypartId,
              'created_at' => now(),
              'updated_at' => now(),
            ]);
          }
        }

        // 複製チェックボックスが選択されている場合、追加の施術内容を登録
        if ($validated['therapy_type'] == 2) {
          $duplicateContents = [];

          if ($request->input('duplicate_massage') == 1) {
            $duplicateContents[] = 7; // マッサージ
          }
          if ($request->input('duplicate_warm_compress') == 1) {
            $duplicateContents[] = 9; // 温罨法
          }
          if ($request->input('duplicate_warm_electric') == 1) {
            $duplicateContents[] = 10; // 温罨法・電気光線器具
          }
          if ($request->input('duplicate_manual_correction') == 1) {
            $duplicateContents[] = 8; // 変形徒手矯正術
          }

          foreach ($duplicateContents as $contentId) {
            $duplicateRecordId = DB::table('records')->insertGetId([
              'clinic_user_id' => $validated['clinic_user_id'],
              'date' => $date,
              'start_time' => $validated['start_time'],
              'end_time' => $validated['end_time'],
              'therapy_type' => $validated['therapy_type'],
              'therapy_category' => $validated['therapy_category'],
              'insurance_category' => $validated['insurance_category'] ?? null,
              'housecall_distance' => $validated['therapy_category'] == 2 ? $distance : null,
              'therapy_days' => count($housecallDistances),
              'consent_expiry' => $validated['consent_expiry'] ?? null,
              'therapy_conetnt_id' => $contentId,
              'bill_category_id' => $validated['bill_category_id'],
              'therapist_id' => $validated['therapist_id'],
              'abstract' => $validated['abstract'] ?? null,
              'created_at' => now(),
              'updated_at' => now(),
            ]);

            // 複製したレコードにも身体部位を保存
            if (isset($validated['bodyparts'])) {
              foreach ($validated['bodyparts'] as $bodypartId) {
                DB::table('bodyparts-records')->insert([
                  'records_id' => $duplicateRecordId,
                  'therapy_type_bodyparts_id' => $bodypartId,
                  'created_at' => now(),
                  'updated_at' => now(),
                ]);
              }
            }
          }
        }
      }

      DB::commit();

      return redirect()
        ->route('records.index', ['clinic_user_id' => $validated['clinic_user_id']])
        ->with('success', '実績データを登録しました。');

    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()
        ->back()
        ->withInput()
        ->withErrors(['error' => 'データの登録に失敗しました：' . $e->getMessage()]);
    }
  }
}
