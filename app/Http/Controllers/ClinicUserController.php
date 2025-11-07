<?php
//-- app/Http/Controllers/ClinicUserController.php --//


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicUserModel;
use App\Models\Insurer;
use App\Models\Insurance;
use App\Models\ConsentingDoctorHistoryMassage;
use Illuminate\Support\Facades\DB;

class ClinicUserController extends Controller
{
  // 保険情報編集：更新処理
  public function insurancesUpdate(Request $request, $id, $insurance_id)
  {
  // セッションからデータを取得
  $data = $request->session()->get('insurances_edit_data');

  if (!$data) {
    return redirect()->route('clinic-users.insurances-info.edit', [$id, $insurance_id])->with('error', 'セッションが切れました。もう一度入力してください。');
  }

  $insurance = Insurance::findOrFail($insurance_id);

  // バリデーション済みデータを使用
  $validated = $data;

  // insurers_idを取得または新規作成
  $insurersId = $insurance->insurers_id; // 既存の保険者IDを保持
  if (isset($validated['selected_insurer']) && $validated['selected_insurer']) {
    // 既存の保険者を選択した場合
    $insurersId = $validated['selected_insurer'];
  } elseif (isset($validated['new_insurer_number']) && $validated['new_insurer_number']) {
    // 新規保険者作成
    $newInsurer = Insurer::create([
      'insurer_number' => $validated['new_insurer_number'],
      'insurer_name' => $validated['new_insurer_name'],
      'postal_code' => $validated['new_postal_code'],
      'address' => $validated['new_address'],
      'recipient_name' => $validated['new_recipient_name']
    ]);
    $insurersId = $newInsurer->id;
  }

  // 保存用データの準備
  $saveData = [
    'insurers_id' => $insurersId,
    'insured_number' => $validated['insured_number'],
    'code_number' => $validated['code_number'] ?? null,
    'account_number' => $validated['account_number'] ?? null,
    'locality_code' => $validated['locality_code'] ?? null,
    'recipient_code' => $validated['recipient_code'] ?? null,
    'license_acquisition_date' => $validated['license_acquisition_date'] ?? null,
    'certification_date' => $validated['certification_date'] ?? null,
    'issue_date' => $validated['issue_date'] ?? null,
    'expiry_date' => $validated['expiry_date'] ?? null,
    'is_redeemed' => $validated['is_redeemed'],
    'insured_name' => $validated['insured_name'] ?? null,
    'is_healthcare_subsidized' => $validated['is_healthcare_subsidized'],
    'public_funds_payer_code' => $validated['public_funds_payer_code'] ?? null,
    'public_funds_recipient_code' => $validated['public_funds_recipient_code'] ?? null,
    'locality_code_family' => $validated['locality_code_family'] ?? null,
    'recipient_code_family' => $validated['recipient_code_family'] ?? null,
  ];

  // 保険種別をIDに変換
  if (isset($validated['insurance_type_1'])) {
    $type1 = DB::table('insurance_types_1')->where('insurance_type_1', $validated['insurance_type_1'])->first();
    $saveData['insurance_type_1_id'] = $type1 ? $type1->id : null;
  }
  if (isset($validated['insurance_type_2'])) {
    $type2 = DB::table('insurance_types_2')->where('insurance_type_2', $validated['insurance_type_2'])->first();
    $saveData['insurance_type_2_id'] = $type2 ? $type2->id : null;
  }
  if (isset($validated['insurance_type_3'])) {
    $type3 = DB::table('insurance_types_3')->where('insurance_type_3', $validated['insurance_type_3'])->first();
    $saveData['insurance_type_3_id'] = $type3 ? $type3->id : null;
  }
  if (isset($validated['insured_person_type'])) {
    $selfFamily = DB::table('self_or_family')->where('subject_type', $validated['insured_person_type'])->first();
    $saveData['self_or_family_id'] = $selfFamily ? $selfFamily->id : null;
  }
  if (isset($validated['relationship_with_clinic_user'])) {
    $rel = DB::table('relationships_with_clinic_user')->where('relationship', $validated['relationship_with_clinic_user'])->first();
    $saveData['relationship_with_clinic_user_id'] = $rel ? $rel->id : null;
  }
  if (isset($validated['expenses_borne_ratio'])) {
    $ratio = DB::table('expenses_borne_ratios')->where('expenses_borne_ratio', $validated['expenses_borne_ratio'])->first();
    $saveData['expenses_borne_ratio_id'] = $ratio ? $ratio->id : null;
  }

  $insurance->fill($saveData);
  $insurance->save();

  // セッションをクリア
  $request->session()->forget('insurances_edit_data');

  return redirect()->route('clinic-users.insurances-info.index', $id)->with('success', '保険情報を更新しました。');
  }
  // 一覧表示
  public function index()
  {
  // DataTablesを使用するため、全件取得
  $clinicUsers = ClinicUserModel::orderBy('id', 'desc')->get();

  return view('clinic-users.cui-index', compact('clinicUsers'));
  }

  public function create()
  {
  return view('clinic-users.cui-registration', [
    'mode' => 'create',
    'title' => '利用者新規登録',
    'clinicUser' => null
  ]);
  }

  // 新規登録：確認画面の表示
  public function confirm(Request $request)
  {
  $validated = $request->validate([
    'clinic_user_name' => 'required|string|max:255',
    'furigana' => 'required|string|max:255',
    'birthday' => 'nullable|date',
    'age' => 'nullable|integer|min:0|max:150',
    'gender_id' => 'nullable|integer|in:1,2',
    'postal_code' => 'required|string|max:8',
    'address_1' => 'required|string|max:255',
    'address_2' => 'required|string|max:255',
    'address_3' => 'required|string|max:255',
    'phone' => 'nullable|string|max:20',
    'cell_phone' => 'nullable|string|max:20',
    'fax' => 'nullable|string|max:20',
    'email' => 'nullable|email|max:255',
    'housecall_distance' => 'nullable|integer|min:0',
    'housecall_additional_distance' => 'nullable|integer|min:0',
    'is_redeemed' => 'nullable|boolean',
    'application_count' => 'nullable|integer|min:0',
    'note' => 'nullable|string|max:1000'
  ]);

  // チェックボックスの処理
  $validated['is_redeemed'] = $request->has('is_redeemed');

  // セッションに保存
  $request->session()->put('registration_data', $validated);

  // 確認画面のラベル設定
  $labels = $this->getLabels();

  return view('registration-review', [
    'data' => $validated,
    'labels' => $labels,
    'back_route' => 'clinic-users.create',
    'store_route' => 'clinic-users.store',
    'page_title' => '利用者登録内容確認',
    'registration_message' => '利用者情報の登録を行います。',
  ]);
  }

  // 新規登録：データ保存処理
  public function store(Request $request)
  {
  // セッションからデータを取得
  $data = $request->session()->get('registration_data');

  if (!$data) {
    return redirect()->route('clinic-users.create')->with('error', 'セッションが切れました。もう一度入力してください。');
  }

  // データベースに保存
  $clinicUser = new ClinicUserModel();
  $clinicUser->fill($data);
  $clinicUser->save();

  // セッションをクリア
  $request->session()->forget('registration_data');

  return view('registration-done', [
    'page_title' => '基本情報登録完了',
    'message' => '入力された内容を登録しました。',
    'index_route' => 'clinic-users.index',
    'index_id' => null,
    'list_route' => null
  ]);
  }

  // 編集画面の表示
  public function edit($id)
  {
  $clinicUser = ClinicUserModel::findOrFail($id);
  return view('clinic-users.cui-registration', [
    'mode' => 'edit',
    'title' => '利用者情報編集',
    'clinicUser' => $clinicUser
  ]);
  }

  // 編集：確認画面の表示
  public function editConfirm(Request $request)
  {
  $validated = $request->validate([
    'id' => 'required|integer|exists:clinic_users,id',
    'clinic_user_name' => 'required|string|max:255',
    'furigana' => 'required|string|max:255',
    'birthday' => 'nullable|date',
    'age' => 'nullable|integer|min:0|max:150',
    'gender_id' => 'nullable|integer|in:1,2',
    'postal_code' => 'required|string|max:8',
    'address_1' => 'required|string|max:255',
    'address_2' => 'required|string|max:255',
    'address_3' => 'required|string|max:255',
    'phone' => 'nullable|string|max:20',
    'cell_phone' => 'nullable|string|max:20',
    'fax' => 'nullable|string|max:20',
    'email' => 'nullable|email|max:255',
    'housecall_distance' => 'nullable|integer|min:0',
    'housecall_additional_distance' => 'nullable|integer|min:0',
    'is_redeemed' => 'nullable|boolean',
    'application_count' => 'nullable|integer|min:0',
    'note' => 'nullable|string|max:1000'
  ]);

  // チェックボックスの処理
  $validated['is_redeemed'] = $request->has('is_redeemed');

  // セッションに保存
  $request->session()->put('edit_data', $validated);

  // 確認画面のラベル設定
  $labels = $this->getLabels();

  return view('registration-review', [
    'data' => $validated,
    'labels' => $labels,
    'back_route' => 'clinic-users.edit',
    'back_id' => $validated['id'],
    'store_route' => 'clinic-users.edit.update',
    'page_title' => '利用者情報更新内容確認',
    'registration_message' => '利用者情報の更新を行います。',
  ]);
  }

  // 編集：データ更新処理
  public function update(Request $request)
  {
  // セッションからデータを取得
  $data = $request->session()->get('edit_data');

  if (!$data) {
    return redirect()->route('clinic-users.index')->with('error', 'セッションが切れました。もう一度入力してください。');
  }

  // データベースを更新
  $clinicUser = ClinicUserModel::findOrFail($data['id']);
  $clinicUser->fill($data);
  $clinicUser->save();

  // セッションをクリア
  $request->session()->forget('edit_data');

  return view('registration-done', [
    'page_title' => '基本情報更新完了',
    'message' => '入力された内容を更新しました。',
    'index_route' => 'clinic-users.index',
    'index_id' => null,
    'list_route' => null
  ]);
  }

  // 削除処理
  public function destroy($id)
  {
  $clinicUser = ClinicUserModel::findOrFail($id);
  $clinicUser->delete();

  return redirect()->route('clinic-users.index')->with('success', '利用者情報を削除しました。');
  }

  // 保険情報画面
  public function insurancesIndex($id)
  {
  $user = ClinicUserModel::findOrFail($id);

  // DataTablesを使用するため、全件取得
  $insurances = Insurance::where('clinic_user_id', $id)
    ->with('insurer')
    ->orderBy('created_at', 'desc')
    ->get();

  return view('clinic-users.insurances.cii-index', [
    'id' => $id,
    'name' => $user->clinic_user_name,
    'insurances' => $insurances
  ]);
  }

  // 保険情報新規登録画面
  public function insurancesCreate($id)
  {
  $user = ClinicUserModel::findOrFail($id);
  $insurers = Insurer::all();

  // セッションからデータを取得して old() にセット
  $sessionData = session('insurances_registration_data');
  if ($sessionData) {
    // セッションデータを再度フラッシュして old() で利用可能にする
    request()->merge($sessionData);
    session()->flashInput($sessionData);
    // セッションデータを保持（確認画面に戻った場合にも利用できるように）
    session()->put('insurances_registration_data', $sessionData);
  }

  return view('clinic-users.insurances.cii-registration', [
    'mode' => 'create',
    'title' => $user->clinic_user_name . ' 様の保険情報新規登録',
    'userId' => $id,
    'insurance' => null,
    'insurers' => $insurers
  ]);
  }

  // 保険情報新規登録：確認画面の表示
  public function insurancesConfirm(Request $request, $id)
  {
  $rules = [
    'insurance_type_1' => 'required|string',
    'insurance_type_2' => 'required|string',
    'insurance_type_3' => 'required|string',
    'insured_person_type' => 'required|string',
    'insured_number' => 'required|integer',
    'code_number' => 'nullable|string',
    'account_number' => 'nullable|string',
    'locality_code' => 'nullable|string',
    'recipient_code' => 'nullable|string',
    'license_acquisition_date' => 'nullable|date',
    'certification_date' => 'nullable|date',
    'issue_date' => 'nullable|date',
    'expenses_borne_ratio' => 'nullable|string',
    'expiry_date' => 'nullable|date',
    'is_redeemed' => 'nullable|boolean',
    'insured_name' => 'nullable|string|max:255',
    'relationship_with_clinic_user' => 'nullable|string',
    'is_healthcare_subsidized' => 'nullable|boolean',
    'public_funds_payer_code' => 'nullable|string',
    'public_funds_recipient_code' => 'nullable|string',
    'locality_code_family' => 'nullable|string',
    'recipient_code_family' => 'nullable|string',
    'selected_insurer' => 'nullable|integer|exists:insurers,id',
    'new_insurer_number' => 'nullable|string|regex:/^\d{6}(\d{2})?$/',
    'new_insurer_name' => 'nullable|string|max:255',
    'new_postal_code' => 'nullable|string|max:8',
    'new_address' => 'nullable|string|max:255',
    'new_recipient_name' => 'nullable|string|max:255'
  ];

  // 選択された保険者がない場合、新規保険者番号は必須
  if (!$request->filled('selected_insurer')) {
    $rules['new_insurer_number'] = 'required|string|regex:/^\d{6}(\d{2})?$/';
  }

  $messages = [
    'new_insurer_number.required' => '保険者番号を入力してください。',
    'new_insurer_number.regex' => '保険者番号は6桁または8桁の数字を入力してください。',
  ];

  $validated = $request->validate($rules, $messages);

  // チェックボックスの処理
  $validated['is_redeemed'] = $request->has('is_redeemed');
  $validated['is_healthcare_subsidized'] = $request->has('is_healthcare_subsidized');

  // セッションに保存
  $request->session()->put('insurances_registration_data', $validated);

  // 確認画面のラベル設定
  $labels = $this->getInsuranceLabels();

  return view('registration-review', [
    'data' => $validated,
    'labels' => $labels,
    'back_route' => 'clinic-users.insurances-info.registration',
    'back_id' => $id,
    'store_route' => 'clinic-users.insurances-info.store',
    'page_title' => '保険情報登録内容確認',
    'registration_message' => '保険情報の登録を行います。',
  ]);
  }

  // 保険情報新規登録
  public function insurancesStore(Request $request, $id)
  {
  // セッションからデータを取得
  $data = $request->session()->get('insurances_registration_data');

  if (!$data) {
    return redirect()->route('clinic-users.insurances-info.registration', $id)->with('error', 'セッションが切れました。もう一度入力してください。');
  }

  // insurers_idを取得または新規作成
  $insurersId = null;
  if (isset($data['selected_insurer']) && $data['selected_insurer']) {
    // 既存の保険者を選択した場合
    $insurersId = $data['selected_insurer'];
  } elseif (isset($data['new_insurer_number']) && $data['new_insurer_number']) {
    // 新規保険者作成
    $newInsurer = Insurer::create([
    'insurer_number' => $data['new_insurer_number'],
    'insurer_name' => $data['new_insurer_name'],
    'postal_code' => $data['new_postal_code'],
    'address' => $data['new_address'],
    'recipient_name' => $data['new_recipient_name']
    ]);
    $insurersId = $newInsurer->id;
  }

  // 文字列をIDに変換
  $saveData = [
    'clinic_user_id' => $id,
    'insurers_id' => $insurersId,
    'insured_number' => $data['insured_number'],
    'code_number' => $data['code_number'] ?? null,
    'account_number' => $data['account_number'] ?? null,
    'locality_code' => $data['locality_code'] ?? null,
    'recipient_code' => $data['recipient_code'] ?? null,
    'license_acquisition_date' => $data['license_acquisition_date'] ?? null,
    'certification_date' => $data['certification_date'] ?? null,
    'issue_date' => $data['issue_date'] ?? null,
    'expiry_date' => $data['expiry_date'] ?? null,
    'is_redeemed' => $data['is_redeemed'] ?? false,
    'insured_name' => $data['insured_name'] ?? null,
    'is_healthcare_subsidized' => $data['is_healthcare_subsidized'] ?? false,
    'public_funds_payer_code' => $data['public_funds_payer_code'] ?? null,
    'public_funds_recipient_code' => $data['public_funds_recipient_code'] ?? null,
    'locality_code_family' => $data['locality_code_family'] ?? null,
    'recipient_code_family' => $data['recipient_code_family'] ?? null,
  ];

  // 保険種別をIDに変換
  if (isset($data['insurance_type_1'])) {
    $type1 = DB::table('insurance_types_1')->where('insurance_type_1', $data['insurance_type_1'])->first();
    $saveData['insurance_type_1_id'] = $type1 ? $type1->id : null;
  }
  if (isset($data['insurance_type_2'])) {
    $type2 = DB::table('insurance_types_2')->where('insurance_type_2', $data['insurance_type_2'])->first();
    $saveData['insurance_type_2_id'] = $type2 ? $type2->id : null;
  }
  if (isset($data['insurance_type_3'])) {
    $type3 = DB::table('insurance_types_3')->where('insurance_type_3', $data['insurance_type_3'])->first();
    $saveData['insurance_type_3_id'] = $type3 ? $type3->id : null;
  }
  if (isset($data['insured_person_type'])) {
    $selfFamily = DB::table('self_or_family')->where('subject_type', $data['insured_person_type'])->first();
    $saveData['self_or_family_id'] = $selfFamily ? $selfFamily->id : null;
  }
  if (isset($data['relationship_with_clinic_user'])) {
    $rel = DB::table('relationships_with_clinic_user')->where('relationship', $data['relationship_with_clinic_user'])->first();
    $saveData['relationship_with_clinic_user_id'] = $rel ? $rel->id : null;
  }
  if (isset($data['expenses_borne_ratio'])) {
    $ratio = DB::table('expenses_borne_ratios')->where('expenses_borne_ratio', $data['expenses_borne_ratio'])->first();
    $saveData['expenses_borne_ratio_id'] = $ratio ? $ratio->id : null;
  }

  // 保険情報保存
  $insurance = new Insurance();
  $insurance->fill($saveData);
  $insurance->save();

  // セッションをクリア
  $request->session()->forget('insurances_registration_data');

  return view('registration-done', [
    'page_title' => '保険情報登録完了',
    'message' => '保険情報を登録しました。',
    'index_route' => 'clinic-users.insurances-info.index',
    'index_id' => $id,
    'list_route' => null
  ])->with('index_id', $id);
  }

  // 同意医師履歴（あんま・マッサージ）
  public function consentsMassageIndex($id)
  {
  $user = ClinicUserModel::findOrFail($id);
  $consentingHistories = ConsentingDoctorHistoryMassage::where('clinic_user_id', $id)
    ->orderBy('created_at', 'desc')
    ->get();

  return view('clinic-users.consents-massage.ccdhm-index', [
    'id' => $id,
    'name' => $user->clinic_user_name,
    'consentingHistories' => $consentingHistories
  ]);
  }

  // 同意医師履歴（あんま・マッサージ）新規登録フォーム
  public function consentsMassageCreate($id)
  {
  $user = ClinicUserModel::findOrFail($id);
  return view('clinic-users.consents-massage.ccdhm-registration', [
    'id' => $id,
    'name' => $user->clinic_user_name
  ]);
  }

  // 同意医師履歴（あんま・マッサージ）登録確認
  public function consentsMassageConfirm(Request $request, $id)
  {
  // バリデーションとセッション保存
  $validated = $request->validate([
    'consenting_doctor_name' => 'required|string|max:255',
    'consenting_date' => 'nullable|date',
    'consenting_start_date' => 'nullable|date',
    'consenting_end_date' => 'nullable|date',
    'benefit_period_start_date' => 'nullable|date',
    'benefit_period_end_date' => 'nullable|date',
    'first_care_date' => 'nullable|date',
    'reconsenting_expiry' => 'nullable|date',
    'onset_and_injury_date' => 'nullable|date',
    'notes' => 'nullable|string|max:255',
  ], [
    'consenting_doctor_name.required' => '同意医師名は必須です。',
    'consenting_doctor_name.max' => '同意医師名は255文字以内で入力してください。',
    'consenting_date.date' => '同意日は正しい日付形式で入力してください。',
    'consenting_start_date.date' => '同意開始日は正しい日付形式で入力してください。',
    'consenting_end_date.date' => '同意終了日は正しい日付形式で入力してください。',
    'benefit_period_start_date.date' => '給付期間開始日は正しい日付形式で入力してください。',
    'benefit_period_end_date.date' => '給付期間終了日は正しい日付形式で入力してください。',
    'first_care_date.date' => '初療日は正しい日付形式で入力してください。',
    'reconsenting_expiry.date' => '再同意期限は正しい日付形式で入力してください。',
    'onset_and_injury_date.date' => '発症・負傷日は正しい日付形式で入力してください。',
    'notes.max' => '備考は255文字以内で入力してください。',
  ]);

  $request->session()->put('consents_massage_registration_data', $validated);

  $labels = $this->getConsentingDoctorHistoryMassageLabels();

  return view('registration-review', [
    'data' => $validated,
    'labels' => $labels,
    'back_route' => 'clinic-users.consenting-doctor-history-massage.registration',
    'back_id' => $id,
    'store_route' => 'clinic-users.consenting-doctor-history-massage.store',
    'page_title' => '同意医師履歴（あんま・マッサージ）登録内容確認',
    'registration_message' => '同意医師履歴（あんま・マッサージ）の登録を行います。',
  ]);
  }

  // 同意医師履歴（あんま・マッサージ）保存
  public function consentsMassageStore(Request $request, $id)
  {
  $data = $request->session()->get('consents_massage_registration_data');

  if (!$data) {
    return redirect()->route('clinic-users.consenting-doctor-history-massage.registration', $id)
      ->with('error', 'セッションが切れました。もう一度入力してください。');
  }

  $data['clinic_user_id'] = $id;

  ConsentingDoctorHistoryMassage::create($data);

  $request->session()->forget('consents_massage_registration_data');

  return redirect()->route('clinic-users.consenting-doctor-history-massage.index', $id)
    ->with('success', '同意医師履歴が登録されました。');
  }

  // 同意医師履歴（あんま・マッサージ）編集フォーム
  public function consentsMassageEdit($id, $history_id)
  {
  $user = ClinicUserModel::findOrFail($id);
  $history = ConsentingDoctorHistoryMassage::findOrFail($history_id);

  return view('clinic-users.consents-massage.ccdhm-edit', [
    'id' => $id,
    'name' => $user->clinic_user_name,
    'history' => $history
  ]);
  }

  // 同意医師履歴（あんま・マッサージ）編集確認
  public function consentsMassageEditConfirm(Request $request, $id, $history_id)
  {
  $validated = $request->validate([
    'consenting_doctor_name' => 'required|string|max:255',
    'consenting_date' => 'nullable|date',
    'consenting_start_date' => 'nullable|date',
    'consenting_end_date' => 'nullable|date',
    'benefit_period_start_date' => 'nullable|date',
    'benefit_period_end_date' => 'nullable|date',
    'first_care_date' => 'nullable|date',
    'reconsenting_expiry' => 'nullable|date',
    'onset_and_injury_date' => 'nullable|date',
    'notes' => 'nullable|string|max:255',
  ], [
    'consenting_doctor_name.required' => '同意医師名は必須です。',
    'consenting_doctor_name.max' => '同意医師名は255文字以内で入力してください。',
    'consenting_date.date' => '同意日は正しい日付形式で入力してください。',
    'consenting_start_date.date' => '同意開始日は正しい日付形式で入力してください。',
    'consenting_end_date.date' => '同意終了日は正しい日付形式で入力してください。',
    'benefit_period_start_date.date' => '給付期間開始日は正しい日付形式で入力してください。',
    'benefit_period_end_date.date' => '給付期間終了日は正しい日付形式で入力してください。',
    'first_care_date.date' => '初療日は正しい日付形式で入力してください。',
    'reconsenting_expiry.date' => '再同意期限は正しい日付形式で入力してください。',
    'onset_and_injury_date.date' => '発症・負傷日は正しい日付形式で入力してください。',
    'notes.max' => '備考は255文字以内で入力してください。',
  ]);

  $request->session()->put('consents_massage_edit_data', $validated);

  $labels = $this->getConsentingDoctorHistoryMassageLabels();

  return view('registration-review', [
    'data' => $validated,
    'labels' => $labels,
    'back_route' => 'clinic-users.consenting-doctor-history-massage.edit',
    'back_id' => $id,
    'back_history_id' => $history_id,
    'store_route' => 'clinic-users.consenting-doctor-history-massage.update',
    'page_title' => '同意医師履歴（あんま・マッサージ）更新内容確認',
    'registration_message' => '同意医師履歴（あんま・マッサージ）の更新を行います。',
  ]);
  }

  // 同意医師履歴（あんま・マッサージ）更新
  public function consentsMassageUpdate(Request $request, $id, $history_id)
  {
  $data = $request->session()->get('consents_massage_edit_data');

  if (!$data) {
    return redirect()->route('clinic-users.consenting-doctor-history-massage.edit', [$id, $history_id])
      ->with('error', 'セッションが切れました。もう一度入力してください。');
  }

  $history = ConsentingDoctorHistoryMassage::findOrFail($history_id);
  $history->update($data);

  $request->session()->forget('consents_massage_edit_data');

  return redirect()->route('clinic-users.consenting-doctor-history-massage.index', $id)
    ->with('success', '同意医師履歴が更新されました。');
  }

  // 同意医師履歴（あんま・マッサージ）複製フォーム
  public function consentsMassageDuplicateForm($id, $history_id)
  {
  $user = ClinicUserModel::findOrFail($id);
  $history = ConsentingDoctorHistoryMassage::findOrFail($history_id);

  return view('clinic-users.consents-massage.ccdhm-duplicate', [
    'id' => $id,
    'name' => $user->clinic_user_name,
    'history' => $history
  ]);
  }

  // 同意医師履歴（あんま・マッサージ）複製確認
  public function consentsMassageDuplicateConfirm(Request $request, $id, $history_id)
  {
  $validated = $request->validate([
    'consenting_doctor_name' => 'required|string|max:255',
    'consenting_date' => 'nullable|date',
    'consenting_start_date' => 'nullable|date',
    'consenting_end_date' => 'nullable|date',
    'benefit_period_start_date' => 'nullable|date',
    'benefit_period_end_date' => 'nullable|date',
    'first_care_date' => 'nullable|date',
    'reconsenting_expiry' => 'nullable|date',
    'onset_and_injury_date' => 'nullable|date',
    'notes' => 'nullable|string|max:255',
  ], [
    'consenting_doctor_name.required' => '同意医師名は必須です。',
    'consenting_doctor_name.max' => '同意医師名は255文字以内で入力してください。',
    'consenting_date.date' => '同意日は正しい日付形式で入力してください。',
    'consenting_start_date.date' => '同意開始日は正しい日付形式で入力してください。',
    'consenting_end_date.date' => '同意終了日は正しい日付形式で入力してください。',
    'benefit_period_start_date.date' => '給付期間開始日は正しい日付形式で入力してください。',
    'benefit_period_end_date.date' => '給付期間終了日は正しい日付形式で入力してください。',
    'first_care_date.date' => '初療日は正しい日付形式で入力してください。',
    'reconsenting_expiry.date' => '再同意期限は正しい日付形式で入力してください。',
    'onset_and_injury_date.date' => '発症・負傷日は正しい日付形式で入力してください。',
    'notes.max' => '備考は255文字以内で入力してください。',
  ]);

  $request->session()->put('consents_massage_duplicate_data', $validated);

  $labels = $this->getConsentingDoctorHistoryMassageLabels();

  return view('registration-review', [
    'data' => $validated,
    'labels' => $labels,
    'back_route' => 'clinic-users.consenting-doctor-history-massage.duplicate',
    'back_id' => $id,
    'back_history_id' => $history_id,
    'store_route' => 'clinic-users.consenting-doctor-history-massage.duplicate.store',
    'page_title' => '同意医師履歴（あんま・マッサージ）複製内容確認',
    'registration_message' => '同意医師履歴（あんま・マッサージ）の複製を行います。',
  ]);
  }

  // 同意医師履歴（あんま・マッサージ）複製保存
  public function consentsMassageDuplicateStore(Request $request, $id, $history_id)
  {
  $data = $request->session()->get('consents_massage_duplicate_data');

  if (!$data) {
    return redirect()->route('clinic-users.consenting-doctor-history-massage.duplicate', [$id, $history_id])
      ->with('error', 'セッションが切れました。もう一度入力してください。');
  }

  $data['clinic_user_id'] = $id;

  ConsentingDoctorHistoryMassage::create($data);

  $request->session()->forget('consents_massage_duplicate_data');

  return redirect()->route('clinic-users.consenting-doctor-history-massage.index', $id)
    ->with('success', '同意医師履歴が複製されました。');
  }

  // 同意医師履歴（あんま・マッサージ）削除
  public function consentsMassageDestroy($id, $history_id)
  {
  $history = ConsentingDoctorHistoryMassage::findOrFail($history_id);
  $history->delete();

  return redirect()->route('clinic-users.consenting-doctor-history-massage.index', $id)
    ->with('success', '同意医師履歴が削除されました。');
  }

  // 同意医師履歴（あんま・マッサージ）印刷
  public function printConsentsMassage($id)
  {
  $user = ClinicUserModel::findOrFail($id);
  $histories = ConsentingDoctorHistoryMassage::where('clinic_user_id', $id)
    ->orderBy('created_at', 'desc')
    ->get();

  return view('clinic-users.consents-massage.ccdhm-history-pdf', [
    'user' => $user,
    'histories' => $histories
  ]);
  }

  // 同意医師履歴（はり・きゅう）
  public function consentsAcupunctureIndex($id)
  {
  $user = ClinicUserModel::findOrFail($id);
  return view('clinic-users.consents-acupuncture.ccdha-index', ['id' => $id, 'name' => $user->clinic_user_name]);
  }

  // 計画情報
  public function plansIndex($id)
  {
  $user = ClinicUserModel::findOrFail($id);
  return view('clinic-users.plans.cpi-index', ['id' => $id, 'name' => $user->clinic_user_name]);
  }

  // 医療保険履歴印刷
  public function printInsurances($id)
  {
  $user = ClinicUserModel::findOrFail($id);

  // 保険情報を新しい順に取得
  $insurances = Insurance::where('clinic_user_id', $id)
    ->with('insurer')
    ->orderBy('created_at', 'desc')
    ->get();

  // TCPDFを使用してPDFを生成
  $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8');

  // PDFメタデータ設定
  $pdf->SetCreator('Sinkyu Massage System');
  $pdf->SetAuthor('System');
  $pdf->SetTitle('医療保険情報履歴一覧表');

  // ヘッダー・フッターを削除
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);

  // マージン設定
  $pdf->SetMargins(10, 10, 10);
  $pdf->SetAutoPageBreak(TRUE, 10);

  // 日本語フォント設定（M+ 1 Medium）
  $pdf->SetFont('mplus1medium', '', 9);

  // ページ追加
  $pdf->AddPage();

  // HTMLコンテンツを生成
  $html = view('clinic-users.insurances.insurance-history-pdf', [
    'user' => $user,
    'insurances' => $insurances
  ])->render();

  // HTMLをPDFに出力
  $pdf->writeHTML($html, true, false, true, false, '');

  // PDFを新規ウィンドウで表示
  return response($pdf->Output('医療保険情報履歴一覧表_' . $user->clinic_user_name . '.pdf', 'I'))
    ->header('Content-Type', 'application/pdf');
  }

  // 保険情報編集画面
  public function insurancesEdit($id, $insurance_id)
  {
  $user = ClinicUserModel::findOrFail($id);
  $insurance = Insurance::with('insurer')->findOrFail($insurance_id);
  $insurers = Insurer::all();
  return view('clinic-users.insurances.cii-registration', [
    'mode' => 'edit',
    'title' => $user->clinic_user_name . ' 様の保険情報編集',
    'userId' => $id,
    'insurance' => $insurance,
    'insurers' => $insurers
  ]);
  }

  // 保険情報編集：確認画面の表示
  public function insurancesEditConfirm(Request $request, $id, $insurance_id)
  {
  $rules = [
    'insurance_type_1' => 'required|string',
    'insurance_type_2' => 'required|string',
    'insurance_type_3' => 'required|string',
    'insured_person_type' => 'required|string',
    'insured_number' => 'required|integer',
    'code_number' => 'nullable|string',
    'account_number' => 'nullable|string',
    'locality_code' => 'nullable|string',
    'recipient_code' => 'nullable|string',
    'license_acquisition_date' => 'nullable|date',
    'certification_date' => 'nullable|date',
    'issue_date' => 'nullable|date',
    'expenses_borne_ratio' => 'nullable|string',
    'expiry_date' => 'nullable|date',
    'is_redeemed' => 'nullable|boolean',
    'insured_name' => 'nullable|string|max:255',
    'relationship_with_clinic_user' => 'nullable|string',
    'is_healthcare_subsidized' => 'nullable|boolean',
    'public_funds_payer_code' => 'nullable|string',
    'public_funds_recipient_code' => 'nullable|string',
    'locality_code_family' => 'nullable|string',
    'recipient_code_family' => 'nullable|string',
    'selected_insurer' => 'nullable|integer|exists:insurers,id',
    'new_insurer_number' => 'nullable|string|regex:/^\d{6}(\d{2})?$/',
    'new_insurer_name' => 'nullable|string|max:255',
    'new_postal_code' => 'nullable|string|max:8',
    'new_address' => 'nullable|string|max:255',
    'new_recipient_name' => 'nullable|string|max:255'
  ];

  // 選択された保険者がない場合、新規保険者番号は必須
  if (!$request->filled('selected_insurer')) {
    $rules['new_insurer_number'] = 'required|string|regex:/^\d{6}(\d{2})?$/';
  }

  $messages = [
    'new_insurer_number.required' => '保険者番号を入力してください。',
    'new_insurer_number.regex' => '保険者番号は6桁または8桁の数字を入力してください。',
  ];

  $validated = $request->validate($rules, $messages);

  // チェックボックスの処理
  $validated['is_redeemed'] = $request->has('is_redeemed');
  $validated['is_healthcare_subsidized'] = $request->has('is_healthcare_subsidized');

  // 保険情報IDをセッションに保存
  $validated['insurance_id'] = $insurance_id;

  // セッションに保存
  $request->session()->put('insurances_edit_data', $validated);

  // 確認画面のラベル設定
  $labels = $this->getInsuranceLabels();

  return view('registration-review', [
    'data' => $validated,
    'labels' => $labels,
    'back_route' => 'clinic-users.insurances-info.edit',
    'back_id' => $id,
    'back_insurance_id' => $insurance_id,
    'store_route' => 'clinic-users.insurances-info.edit.update',
    'page_title' => '保険情報更新内容確認',
    'registration_message' => '保険情報の更新を行います。',
  ]);
  }

  // 保険情報複製：フォーム表示
  public function insurancesDuplicateForm($id, $insurance_id)
  {
  $user = ClinicUserModel::findOrFail($id);
  $insurance = Insurance::with('insurer')->findOrFail($insurance_id);
  $insurers = Insurer::all();
  return view('clinic-users.insurances.cii-registration', [
    'mode' => 'duplicate',
    'title' => $user->clinic_user_name . ' 様の保険情報複製',
    'userId' => $id,
    'insurance' => $insurance,
    'insurers' => $insurers
  ]);
  }

  // 保険情報複製：確認画面の表示
  public function insurancesDuplicateConfirm(Request $request, $id, $insurance_id)
  {
  $rules = [
    'insurance_type_1' => 'required|string',
    'insurance_type_2' => 'required|string',
    'insurance_type_3' => 'required|string',
    'insured_person_type' => 'required|string',
    'insured_number' => 'required|integer',
    'code_number' => 'nullable|string',
    'account_number' => 'nullable|string',
    'locality_code' => 'nullable|string',
    'recipient_code' => 'nullable|string',
    'license_acquisition_date' => 'nullable|date',
    'certification_date' => 'nullable|date',
    'issue_date' => 'nullable|date',
    'expenses_borne_ratio' => 'nullable|string',
    'expiry_date' => 'nullable|date',
    'is_redeemed' => 'nullable|boolean',
    'insured_name' => 'nullable|string|max:255',
    'relationship_with_clinic_user' => 'nullable|string',
    'is_healthcare_subsidized' => 'nullable|boolean',
    'public_funds_payer_code' => 'nullable|string',
    'public_funds_recipient_code' => 'nullable|string',
    'locality_code_family' => 'nullable|string',
    'recipient_code_family' => 'nullable|string',
    'selected_insurer' => 'nullable|integer|exists:insurers,id',
    'new_insurer_number' => 'nullable|string|regex:/^\d{6}(\d{2})?$/',
    'new_insurer_name' => 'nullable|string|max:255',
    'new_postal_code' => 'nullable|string|max:8',
    'new_address' => 'nullable|string|max:255',
    'new_recipient_name' => 'nullable|string|max:255'
  ];

  // 選択された保険者がない場合、新規保険者番号は必須
  if (!$request->filled('selected_insurer')) {
    $rules['new_insurer_number'] = 'required|string|regex:/^\d{6}(\d{2})?$/';
  }

  $messages = [
    'new_insurer_number.required' => '保険者番号を入力してください。',
    'new_insurer_number.regex' => '保険者番号は6桁または8桁の数字を入力してください。',
  ];

  $validated = $request->validate($rules, $messages);

  // チェックボックスの処理
  $validated['is_redeemed'] = $request->has('is_redeemed');
  $validated['is_healthcare_subsidized'] = $request->has('is_healthcare_subsidized');

  // 保険情報IDをセッションに保存
  $validated['insurance_id'] = $insurance_id;

  // セッションに保存
  $request->session()->put('insurances_duplicate_data', $validated);

  // 確認画面のラベル設定
  $labels = $this->getInsuranceLabels();

  return view('registration-review', [
    'data' => $validated,
    'labels' => $labels,
    'back_route' => 'clinic-users.insurances-info.duplicate',
    'back_id' => $id,
    'back_insurance_id' => $insurance_id,
    'store_route' => 'clinic-users.insurances-info.duplicate.store',
    'page_title' => '保険情報複製内容確認',
    'registration_message' => '保険情報の複製を行います。',
  ]);
  }

  // 保険情報複製：実行
  public function insurancesDuplicateStore(Request $request, $id, $insurance_id)
  {
  // セッションからデータを取得
  $data = $request->session()->get('insurances_duplicate_data');

  if (!$data) {
    return redirect()->route('clinic-users.insurances-info.duplicate', [$id, $insurance_id])->with('error', 'セッションが切れました。もう一度入力してください。');
  }

  // バリデーション済みデータを使用
  $validated = $data;

  // clinic_user_idを設定
  $clinicUserId = $id;

  // insurers_idを取得または新規作成
  $insurersId = null;
  if (isset($validated['selected_insurer']) && $validated['selected_insurer']) {
    // 既存の保険者を選択した場合
    $insurersId = $validated['selected_insurer'];
  } elseif (isset($validated['new_insurer_number']) && $validated['new_insurer_number']) {
    // 新規保険者作成
    $newInsurer = Insurer::create([
      'insurer_number' => $validated['new_insurer_number'],
      'insurer_name' => $validated['new_insurer_name'],
      'postal_code' => $validated['new_postal_code'],
      'address' => $validated['new_address'],
      'recipient_name' => $validated['new_recipient_name']
    ]);
    $insurersId = $newInsurer->id;
  }

  // 保存用データの準備
  $saveData = [
    'clinic_user_id' => $clinicUserId,
    'insurers_id' => $insurersId,
    'insured_number' => $validated['insured_number'],
    'code_number' => $validated['code_number'] ?? null,
    'account_number' => $validated['account_number'] ?? null,
    'locality_code' => $validated['locality_code'] ?? null,
    'recipient_code' => $validated['recipient_code'] ?? null,
    'license_acquisition_date' => $validated['license_acquisition_date'] ?? null,
    'certification_date' => $validated['certification_date'] ?? null,
    'issue_date' => $validated['issue_date'] ?? null,
    'expiry_date' => $validated['expiry_date'] ?? null,
    'is_redeemed' => $validated['is_redeemed'],
    'insured_name' => $validated['insured_name'] ?? null,
    'is_healthcare_subsidized' => $validated['is_healthcare_subsidized'],
    'public_funds_payer_code' => $validated['public_funds_payer_code'] ?? null,
    'public_funds_recipient_code' => $validated['public_funds_recipient_code'] ?? null,
    'locality_code_family' => $validated['locality_code_family'] ?? null,
    'recipient_code_family' => $validated['recipient_code_family'] ?? null,
  ];

  // 保険種別をIDに変換
  if (isset($validated['insurance_type_1'])) {
    $type1 = DB::table('insurance_types_1')->where('insurance_type_1', $validated['insurance_type_1'])->first();
    $saveData['insurance_type_1_id'] = $type1 ? $type1->id : null;
  }
  if (isset($validated['insurance_type_2'])) {
    $type2 = DB::table('insurance_types_2')->where('insurance_type_2', $validated['insurance_type_2'])->first();
    $saveData['insurance_type_2_id'] = $type2 ? $type2->id : null;
  }
  if (isset($validated['insurance_type_3'])) {
    $type3 = DB::table('insurance_types_3')->where('insurance_type_3', $validated['insurance_type_3'])->first();
    $saveData['insurance_type_3_id'] = $type3 ? $type3->id : null;
  }
  if (isset($validated['insured_person_type'])) {
    $selfFamily = DB::table('self_or_family')->where('subject_type', $validated['insured_person_type'])->first();
    $saveData['self_or_family_id'] = $selfFamily ? $selfFamily->id : null;
  }
  if (isset($validated['relationship_with_clinic_user'])) {
    $rel = DB::table('relationships_with_clinic_user')->where('relationship', $validated['relationship_with_clinic_user'])->first();
    $saveData['relationship_with_clinic_user_id'] = $rel ? $rel->id : null;
  }
  if (isset($validated['expenses_borne_ratio'])) {
    $ratio = DB::table('expenses_borne_ratios')->where('expenses_borne_ratio', $validated['expenses_borne_ratio'])->first();
    $saveData['expenses_borne_ratio_id'] = $ratio ? $ratio->id : null;
  }

  // 保険情報保存
  $insurance = new Insurance();
  $insurance->fill($saveData);
  $insurance->save();

  // セッションをクリア
  $request->session()->forget('insurances_duplicate_data');

  return redirect()->route('clinic-users.insurances-info.index', $id)->with('success', '保険情報を複製しました。');
  }

  // 保険情報削除
  public function insurancesDestroy($id, $insurance_id)
  {
  $insurance = Insurance::findOrFail($insurance_id);
  $insurance->delete();
  return redirect()->route('clinic-users.insurances-info.index', $id)->with('success', '保険情報を削除しました。');
  }

  // ラベル設定（共通処理）
  private function getLabels()
  {
  return [
    'clinic_user_name' => '利用者氏名',
    'furigana' => 'フリガナ',
    'birthday' => '生年月日',
    'age' => '年齢',
    'gender_id' => '性別',
    'postal_code' => '郵便番号',
    'address_1' => '都道府県',
    'address_2' => '市区町村番地以下',
    'address_3' => 'アパート・マンション名等',
    'phone' => '電話番号',
    'cell_phone' => '携帯番号',
    'fax' => 'FAX番号',
    'email' => 'メールアドレス',
    'housecall_distance' => '往診距離（合計）',
    'housecall_additional_distance' => '往診加算距離',
    'is_redeemed' => '償還対象',
    'application_count' => '申請書提出開始回数[大阪市のみ]',
    'note' => 'メモ'
  ];
  }

  // 保険情報ラベル設定（共通処理）
  private function getInsuranceLabels()
  {
  return [
    'insurance_type_1' => '保険種別１',
    'insurance_type_2' => '保険種別２',
    'insurance_type_3' => '保険種別３',
    'insured_person_type' => '本人・家族',
    'insured_number' => '被保険者番号',
    'code_number' => '記号',
    'account_number' => '番号',
    'locality_code' => '区市町村番号',
    'recipient_code' => '受給者番号',
    'license_acquisition_date' => '資格取得年月日',
    'certification_date' => '認定年月日',
    'issue_date' => '発行（交付）年月日',
    'expenses_borne_ratio' => '一部負担金の割合',
    'expiry_date' => '有効期限',
    'is_redeemed' => '償還対象',
    'insured_name' => '被保険者氏名',
    'relationship_with_clinic_user' => '利用者との続柄',
    'is_healthcare_subsidized' => '医療助成対象',
    'public_funds_payer_code' => '公費負担者番号',
    'public_funds_recipient_code' => '公費受給者番号',
    'locality_code_family' => '区市町村番号（家族）',
    'recipient_code_family' => '受給者番号（家族）',
    'new_insurer_number' => '保険者番号',
    'new_insurer_name' => '保険者名称',
    'new_postal_code' => '郵便番号',
    'new_address' => '住所',
    'new_recipient_name' => '提出先名称'
  ];
  }

  // 同意医師履歴（あんま・マッサージ）のラベル取得
  private function getConsentingDoctorHistoryMassageLabels()
  {
    return [
      'consenting_doctor_name' => '同意医師名',
      'consenting_date' => '同意日',
      'consenting_start_date' => '同意開始日',
      'consenting_end_date' => '同意終了日',
      'benefit_period_start_date' => '給付期間開始日',
      'benefit_period_end_date' => '給付期間終了日',
      'first_care_date' => '初療日',
      'reconsenting_expiry' => '再同意期限',
      'onset_and_injury_date' => '発症・負傷日',
      'notes' => '備考'
    ];
  }
}
