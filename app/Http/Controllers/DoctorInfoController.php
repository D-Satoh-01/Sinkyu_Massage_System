<?php
//-- app/Http/Controllers/DoctorInfoController.php --//


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorInfoController extends Controller
{
  // 医師情報一覧表示
  public function index()
  {
    // DataTablesを使用するため、全件取得
    $doctors = DB::table('doctors')
      ->leftJoin('medical_institutions', 'doctors.medical_institutions_id', '=', 'medical_institutions.id')
      ->select(
        'doctors.*',
        'medical_institutions.medical_institution_name'
      )
      ->orderBy('doctors.id', 'desc')
      ->get();

    return view('doctors-info.di-index', compact('doctors'));
  }

  // 医師情報新規登録画面表示
  public function create()
  {
    // セッションに保存されたデータがあれば、それをフラッシュデータとして設定
    $sessionData = session()->get('doctor_registration_data');
    if ($sessionData) {
      request()->merge($sessionData);
      session()->flashInput($sessionData);
      // セッションデータを保持（確認画面に戻った場合にも利用できるように）
      session()->put('doctor_registration_data', $sessionData);
    }

    // 医療機関マスタを取得
    $medicalInstitutions = DB::table('medical_institutions')
      ->orderBy('medical_institution_name', 'asc')
      ->get();

    return view('doctors-info.di-registration', [
      'mode' => 'create',
      'title' => '医師情報新規登録',
      'doctor' => null,
      'medicalInstitutions' => $medicalInstitutions
    ]);
  }

  // 医師情報新規登録：確認画面の表示
  public function confirm(Request $request)
  {
    $rules = [
      'doctor_name' => 'required|max:255',
      'furigana' => 'nullable|max:255',
      'medical_institutions_id' => 'nullable|exists:medical_institutions,id',
      'new_medical_institution_name' => 'nullable|max:255',
      'postal_code' => 'nullable|max:8',
      'address_1' => 'nullable|max:255',
      'address_2' => 'nullable|max:255',
      'address_3' => 'nullable|max:255',
      'phone' => 'nullable|max:255',
      'cell_phone' => 'nullable|max:255',
      'fax' => 'nullable|max:255',
      'email' => 'nullable|email|max:255',
      'note' => 'nullable|max:255',
    ];

    $messages = [
      'doctor_name.required' => '医師名は必須です。',
      'doctor_name.max' => '医師名は255文字以内で入力してください。',
      'medical_institutions_id.exists' => '選択された医療機関が存在しません。',
      'email.email' => '正しいメールアドレス形式で入力してください。',
    ];

    $validated = $request->validate($rules, $messages);

    // セッションに保存
    $request->session()->put('doctor_registration_data', $validated);

    // 確認画面のラベル設定
    $labels = $this->getDoctorLabels();

    return view('registration-review', [
      'data' => $validated,
      'labels' => $labels,
      'back_route' => 'doctors-info.create',
      'store_route' => 'doctors-info.store',
      'page_title' => '医師情報登録内容確認',
      'registration_message' => '医師情報の登録を行います。',
    ]);
  }

  // 医師情報新規登録処理
  public function store(Request $request)
  {
    // セッションからデータを取得
    $data = $request->session()->get('doctor_registration_data');

    if (!$data) {
      return redirect()->route('doctors-info.create')->with('error', 'セッションが切れました。もう一度入力してください。');
    }

    // 医療機関の新規登録処理
    $medicalInstitutionId = $data['medical_institutions_id'] ?? null;

    if (!$medicalInstitutionId && !empty($data['new_medical_institution_name'])) {
      $medicalInstitutionId = DB::table('medical_institutions')->insertGetId([
        'medical_institution_name' => $data['new_medical_institution_name'],
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    // データ挿入
    DB::table('doctors')->insert([
      'doctor_name' => $data['doctor_name'],
      'furigana' => $data['furigana'] ?? null,
      'medical_institutions_id' => $medicalInstitutionId,
      'postal_code' => $data['postal_code'] ?? null,
      'address_1' => $data['address_1'] ?? null,
      'address_2' => $data['address_2'] ?? null,
      'address_3' => $data['address_3'] ?? null,
      'phone' => $data['phone'] ?? null,
      'cell_phone' => $data['cell_phone'] ?? null,
      'fax' => $data['fax'] ?? null,
      'email' => $data['email'] ?? null,
      'note' => $data['note'] ?? null,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // セッションから登録データを削除
    $request->session()->forget('doctor_registration_data');

    return redirect()->route('doctors-info.index')->with('success', '医師情報を登録しました。');
  }

  // 医師情報編集画面表示
  public function edit($id)
  {
    // セッションに保存されたデータがあれば、それをフラッシュデータとして設定
    $sessionData = session()->get('doctor_edit_data');
    $sessionId = session()->get('doctor_edit_id');
    if ($sessionData && $sessionId == $id) {
      request()->merge($sessionData);
      session()->flashInput($sessionData);
      // セッションデータを保持（確認画面に戻った場合にも利用できるように）
      session()->put('doctor_edit_data', $sessionData);
      session()->put('doctor_edit_id', $sessionId);
    }

    $doctor = DB::table('doctors')->where('id', $id)->first();

    if (!$doctor) {
      return redirect()->route('doctors-info.index')->with('error', '医師情報が見つかりません。');
    }

    $medicalInstitutions = DB::table('medical_institutions')
      ->orderBy('medical_institution_name', 'asc')
      ->get();

    return view('doctors-info.di-registration', [
      'mode' => 'edit',
      'title' => '医師情報編集',
      'doctor' => $doctor,
      'medicalInstitutions' => $medicalInstitutions
    ]);
  }

  // 医師情報編集：確認画面の表示
  public function editConfirm(Request $request, $id)
  {
    $rules = [
      'doctor_name' => 'required|max:255',
      'furigana' => 'nullable|max:255',
      'medical_institutions_id' => 'nullable|exists:medical_institutions,id',
      'new_medical_institution_name' => 'nullable|max:255',
      'postal_code' => 'nullable|max:8',
      'address_1' => 'nullable|max:255',
      'address_2' => 'nullable|max:255',
      'address_3' => 'nullable|max:255',
      'phone' => 'nullable|max:255',
      'cell_phone' => 'nullable|max:255',
      'fax' => 'nullable|max:255',
      'email' => 'nullable|email|max:255',
      'note' => 'nullable|max:255',
    ];

    $messages = [
      'doctor_name.required' => '医師名は必須です。',
      'doctor_name.max' => '医師名は255文字以内で入力してください。',
      'medical_institutions_id.exists' => '選択された医療機関が存在しません。',
      'email.email' => '正しいメールアドレス形式で入力してください。',
    ];

    $validated = $request->validate($rules, $messages);

    // セッションに保存
    $request->session()->put('doctor_edit_data', $validated);
    $request->session()->put('doctor_edit_id', $id);

    // 確認画面のラベル設定
    $labels = $this->getDoctorLabels();

    return view('registration-review', [
      'data' => $validated,
      'labels' => $labels,
      'back_route' => 'doctors-info.edit',
      'back_id' => $id,
      'store_route' => 'doctors-info.update',
      'page_title' => '医師情報編集内容確認',
      'registration_message' => '医師情報の更新を行います。',
    ]);
  }

  // 医師情報更新処理
  public function update(Request $request, $id)
  {
    $data = $request->session()->get('doctor_edit_data');
    $sessionId = $request->session()->get('doctor_edit_id');

    if (!$data || $sessionId != $id) {
      return redirect()->route('doctors-info.edit', $id)->with('error', 'セッションが切れました。もう一度入力してください。');
    }

    // 医療機関の新規登録処理
    $medicalInstitutionId = $data['medical_institutions_id'] ?? null;

    if (!$medicalInstitutionId && !empty($data['new_medical_institution_name'])) {
      $medicalInstitutionId = DB::table('medical_institutions')->insertGetId([
        'medical_institution_name' => $data['new_medical_institution_name'],
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    // データ更新
    DB::table('doctors')->where('id', $id)->update([
      'doctor_name' => $data['doctor_name'],
      'furigana' => $data['furigana'] ?? null,
      'medical_institutions_id' => $medicalInstitutionId,
      'postal_code' => $data['postal_code'] ?? null,
      'address_1' => $data['address_1'] ?? null,
      'address_2' => $data['address_2'] ?? null,
      'address_3' => $data['address_3'] ?? null,
      'phone' => $data['phone'] ?? null,
      'cell_phone' => $data['cell_phone'] ?? null,
      'fax' => $data['fax'] ?? null,
      'email' => $data['email'] ?? null,
      'note' => $data['note'] ?? null,
      'updated_at' => now(),
    ]);

    // セッションから編集データを削除
    $request->session()->forget('doctor_edit_data');
    $request->session()->forget('doctor_edit_id');

    return redirect()->route('doctors-info.index')->with('success', '医師情報を更新しました。');
  }

  // 医師情報複製画面表示
  public function duplicate($id)
  {
    // セッションに保存されたデータがあれば、それをフラッシュデータとして設定
    $sessionData = session()->get('doctor_duplicate_data');
    if ($sessionData) {
      request()->merge($sessionData);
      session()->flashInput($sessionData);
      // セッションデータを保持（確認画面に戻った場合にも利用できるように）
      session()->put('doctor_duplicate_data', $sessionData);
    }

    $doctor = DB::table('doctors')->where('id', $id)->first();

    if (!$doctor) {
      return redirect()->route('doctors-info.index')->with('error', '医師情報が見つかりません。');
    }

    $medicalInstitutions = DB::table('medical_institutions')
      ->orderBy('medical_institution_name', 'asc')
      ->get();

    return view('doctors-info.di-registration', [
      'mode' => 'duplicate',
      'title' => '医師情報複製',
      'doctor' => $doctor,
      'medicalInstitutions' => $medicalInstitutions
    ]);
  }

  // 医師情報複製：確認画面の表示
  public function duplicateConfirm(Request $request)
  {
    $rules = [
      'source_doctor_id' => 'required|exists:doctors,id',
      'doctor_name' => 'required|max:255',
      'furigana' => 'nullable|max:255',
      'medical_institutions_id' => 'nullable|exists:medical_institutions,id',
      'new_medical_institution_name' => 'nullable|max:255',
      'postal_code' => 'nullable|max:8',
      'address_1' => 'nullable|max:255',
      'address_2' => 'nullable|max:255',
      'address_3' => 'nullable|max:255',
      'phone' => 'nullable|max:255',
      'cell_phone' => 'nullable|max:255',
      'fax' => 'nullable|max:255',
      'email' => 'nullable|email|max:255',
      'note' => 'nullable|max:255',
    ];

    $messages = [
      'doctor_name.required' => '医師名は必須です。',
      'doctor_name.max' => '医師名は255文字以内で入力してください。',
      'medical_institutions_id.exists' => '選択された医療機関が存在しません。',
      'email.email' => '正しいメールアドレス形式で入力してください。',
    ];

    $validated = $request->validate($rules, $messages);

    // セッションに保存
    $request->session()->put('doctor_duplicate_data', $validated);
    $request->session()->put('doctor_duplicate_source_id', $validated['source_doctor_id']);

    // 確認画面のラベル設定
    $labels = $this->getDoctorLabels();

    return view('registration-review', [
      'data' => $validated,
      'labels' => $labels,
      'back_route' => 'doctors-info.duplicate',
      'back_id' => $validated['source_doctor_id'],
      'store_route' => 'doctors-info.duplicate.store',
      'page_title' => '医師情報複製内容確認',
      'registration_message' => '医師情報の複製登録を行います。',
    ]);
  }

  // 医師情報複製登録処理
  public function duplicateStore(Request $request)
  {
    $data = $request->session()->get('doctor_duplicate_data');

    if (!$data) {
      return redirect()->route('doctors-info.index')->with('error', 'セッションが切れました。もう一度入力してください。');
    }

    // 医療機関の新規登録処理
    $medicalInstitutionId = $data['medical_institutions_id'] ?? null;

    if (!$medicalInstitutionId && !empty($data['new_medical_institution_name'])) {
      $medicalInstitutionId = DB::table('medical_institutions')->insertGetId([
        'medical_institution_name' => $data['new_medical_institution_name'],
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    // データ挿入
    DB::table('doctors')->insert([
      'doctor_name' => $data['doctor_name'],
      'furigana' => $data['furigana'] ?? null,
      'medical_institutions_id' => $medicalInstitutionId,
      'postal_code' => $data['postal_code'] ?? null,
      'address_1' => $data['address_1'] ?? null,
      'address_2' => $data['address_2'] ?? null,
      'address_3' => $data['address_3'] ?? null,
      'phone' => $data['phone'] ?? null,
      'cell_phone' => $data['cell_phone'] ?? null,
      'fax' => $data['fax'] ?? null,
      'email' => $data['email'] ?? null,
      'note' => $data['note'] ?? null,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // セッションから複製データを削除
    $request->session()->forget('doctor_duplicate_data');

    return redirect()->route('doctors-info.index')->with('success', '医師情報を複製登録しました。');
  }

  // 医師情報のラベル取得
  private function getDoctorLabels()
  {
    return [
      'doctor_name' => '医師名',
      'furigana' => 'フリガナ',
      'new_medical_institution_name' => '新規医療機関名',
      'postal_code' => '郵便番号',
      'address_1' => '都道府県',
      'address_2' => '市区町村番地以下',
      'address_3' => 'アパート・マンション名等',
      'phone' => '電話番号',
      'cell_phone' => '携帯番号',
      'fax' => 'FAX番号',
      'email' => 'メールアドレス',
      'note' => 'メモ',
    ];
  }

  // 医師情報削除
  public function destroy($id)
  {
    DB::table('doctors')->where('id', $id)->delete();
    return redirect()->route('doctors-info.index')->with('success', '医師情報を削除しました。');
  }
}
