<?php
//-- app/Http/Controllers/TherapistsController.php --//


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TherapistRequest;

class TherapistsController extends Controller
{
  // 施術者情報一覧表示
  public function index()
  {
    // DataTablesを使用するため、全件取得
    $therapists = DB::table('therapists')
      ->orderBy('id', 'desc')
      ->get();

    return view('therapists.therapists_index', [
      'therapists' => $therapists,
      'page_header_title' => '施術者情報',
    ]);
  }

  // 施術者情報新規登録画面表示
  public function create()
  {
    // セッションに保存されたデータがあれば、それをフラッシュデータとして設定
    $sessionData = session()->get('therapists_registration_data');
    if ($sessionData) {
      request()->merge($sessionData);
      session()->flashInput($sessionData);
      // セッションデータを保持（確認画面に戻った場合にも利用できるように）
      session()->put('therapists_registration_data', $sessionData);
    }

    return view('therapists.therapists_registration', [
      'mode' => 'create',
      'page_header_title' => '施術者情報新規登録',
      'therapist' => null
    ]);
  }

  // 施術者情報新規登録：確認画面の表示
  public function confirm(TherapistRequest $request)
  {
    $validated = $request->validated();

    // セッションに保存
    $request->session()->put('therapists_registration_data', $validated);

    // 確認画面のラベル設定
    $labels = $this->getTherapistLabels();

    return view('registration-review', [
      'data' => $validated,
      'labels' => $labels,
      'back_route' => 'therapists.create',
      'store_route' => 'therapists.store',
      'page_header_title' => '施術者情報登録内容確認',
      'registration_message' => '施術者情報の登録を行います。',
    ]);
  }

  // 施術者情報新規登録処理
  public function store(Request $request)
  {
    // セッションからデータを取得
    $data = $request->session()->get('therapists_registration_data');

    if (!$data) {
      return redirect()->route('therapists.create')->with('error', 'セッションが切れました。もう一度入力してください。');
    }

    // データ挿入
    DB::table('therapists')->insert([
      'therapist_name' => $data['therapist_name'],
      'furigana' => $data['furigana'] ?? null,
      'postal_code' => $data['postal_code'] ?? null,
      'address_1' => $data['address_1'] ?? null,
      'address_2' => $data['address_2'] ?? null,
      'address_3' => $data['address_3'] ?? null,
      'phone' => $data['phone'] ?? null,
      'cell_phone' => $data['cell_phone'] ?? null,
      'fax' => $data['fax'] ?? null,
      'email' => $data['email'] ?? null,
      'license_hari_id' => $data['license_hari_id'] ?? null,
      'license_hari_number' => $data['license_hari_number'] ?? null,
      'license_hari_issued_date' => $data['license_hari_issued_date'] ?? null,
      'license_kyu_id' => $data['license_kyu_id'] ?? null,
      'license_kyu_number' => $data['license_kyu_number'] ?? null,
      'license_kyu_issued_date' => $data['license_kyu_issued_date'] ?? null,
      'license_massage_id' => $data['license_massage_id'] ?? null,
      'license_massage_number' => $data['license_massage_number'] ?? null,
      'license_massage_issued_date' => $data['license_massage_issued_date'] ?? null,
      'member_number' => $data['member_number'] ?? null,
      'note' => $data['note'] ?? null,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // セッションから登録データを削除
    $request->session()->forget('therapists_registration_data');

    return redirect()->route('therapists.index')->with('success', '施術者情報を登録しました。');
  }

  // 施術者情報編集画面表示
  public function edit($id)
  {
    // セッションに保存されたデータがあれば、それをフラッシュデータとして設定
    $sessionData = session()->get('therapists_edit_data');
    $sessionId = session()->get('therapists_edit_id');
    if ($sessionData && $sessionId == $id) {
      request()->merge($sessionData);
      session()->flashInput($sessionData);
      // セッションデータを保持（確認画面に戻った場合にも利用できるように）
      session()->put('therapists_edit_data', $sessionData);
      session()->put('therapists_edit_id', $sessionId);
    }

    $therapist = DB::table('therapists')->where('id', $id)->first();

    if (!$therapist) {
      return redirect()->route('therapists.index')->with('error', '施術者情報が見つかりません。');
    }

    return view('therapists.therapists_registration', [
      'mode' => 'edit',
      'page_header_title' => '施術者情報編集',
      'therapist' => $therapist
    ]);
  }

  // 施術者情報編集：確認画面の表示
  public function editConfirm(TherapistRequest $request, $id)
  {
    $validated = $request->validated();

    // セッションに保存
    $request->session()->put('therapists_edit_data', $validated);
    $request->session()->put('therapists_edit_id', $id);

    // 確認画面のラベル設定
    $labels = $this->getTherapistLabels();

    return view('registration-review', [
      'data' => $validated,
      'labels' => $labels,
      'back_route' => 'therapists.edit',
      'back_id' => $id,
      'store_route' => 'therapists.update',
      'page_header_title' => '施術者情報編集内容確認',
      'registration_message' => '施術者情報の更新を行います。',
    ]);
  }

  // 施術者情報更新処理
  public function update(Request $request, $id)
  {
    $data = $request->session()->get('therapists_edit_data');
    $sessionId = $request->session()->get('therapists_edit_id');

    if (!$data || $sessionId != $id) {
      return redirect()->route('therapists.edit', $id)->with('error', 'セッションが切れました。もう一度入力してください。');
    }

    // データ更新
    DB::table('therapists')->where('id', $id)->update([
      'therapist_name' => $data['therapist_name'],
      'furigana' => $data['furigana'] ?? null,
      'postal_code' => $data['postal_code'] ?? null,
      'address_1' => $data['address_1'] ?? null,
      'address_2' => $data['address_2'] ?? null,
      'address_3' => $data['address_3'] ?? null,
      'phone' => $data['phone'] ?? null,
      'cell_phone' => $data['cell_phone'] ?? null,
      'fax' => $data['fax'] ?? null,
      'email' => $data['email'] ?? null,
      'license_hari_id' => $data['license_hari_id'] ?? null,
      'license_hari_number' => $data['license_hari_number'] ?? null,
      'license_hari_issued_date' => $data['license_hari_issued_date'] ?? null,
      'license_kyu_id' => $data['license_kyu_id'] ?? null,
      'license_kyu_number' => $data['license_kyu_number'] ?? null,
      'license_kyu_issued_date' => $data['license_kyu_issued_date'] ?? null,
      'license_massage_id' => $data['license_massage_id'] ?? null,
      'license_massage_number' => $data['license_massage_number'] ?? null,
      'license_massage_issued_date' => $data['license_massage_issued_date'] ?? null,
      'member_number' => $data['member_number'] ?? null,
      'note' => $data['note'] ?? null,
      'updated_at' => now(),
    ]);

    // セッションから編集データを削除
    $request->session()->forget('therapists_edit_data');
    $request->session()->forget('therapists_edit_id');

    return redirect()->route('therapists.index')->with('success', '施術者情報を更新しました。');
  }

  // 施術者情報のラベル取得
  private function getTherapistLabels()
  {
    return [
      'therapist_name' => '施術者名',
      'furigana' => 'フリガナ',
      'postal_code' => '郵便番号',
      'address_1' => '都道府県',
      'address_2' => '市区町村番地以下',
      'address_3' => 'アパート・マンション名等',
      'phone' => '電話番号',
      'cell_phone' => '携帯番号',
      'fax' => 'FAX番号',
      'email' => 'メールアドレス',
      'license_hari_id' => '資格（はり）免許証記号番号',
      'license_hari_number' => '資格（はり）免許証番号',
      'license_hari_issued_date' => '資格（はり）免許証交付年月日',
      'license_kyu_id' => '資格（きゅう）免許証記号番号',
      'license_kyu_number' => '資格（きゅう）免許証番号',
      'license_kyu_issued_date' => '資格（きゅう）免許証交付年月日',
      'license_massage_id' => '資格（あん摩・マッサージ）免許証記号番号',
      'license_massage_number' => '資格（あん摩・マッサージ）免許証番号',
      'license_massage_issued_date' => '資格（あん摩・マッサージ）免許証交付年月日',
      'member_number' => '大阪市発行の会員番号',
      'note' => 'メモ',
    ];
  }

  // 施術者情報削除
  public function destroy($id)
  {
    DB::table('therapists')->where('id', $id)->delete();
    return redirect()->route('therapists.index')->with('success', '施術者情報を削除しました。');
  }
}
