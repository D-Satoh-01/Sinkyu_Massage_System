<?php
// app/Http/Controllers/ClinicUserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicUserModel;

class ClinicUserController extends Controller
{
  public function create()
  {
    return view('clinic-users-info.cui-registration');
  }

  // 確認画面の表示
  public function confirm(Request $request)
  {
    $validated = $request->validate([
      'clinic_user_name' => 'required|string|max:255',
      'furigana' => 'required|string|max:255',
      'birthday' => 'nullable|date',
      'age' => 'required|integer|min:0|max:150',
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
    $labels = [
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

    return view('registration-review', [
      'data' => $validated,
      'labels' => $labels,
      'back_route' => 'cui-registration',
      'store_route' => 'cui-registration.store',
      'page_title' => '利用者登録内容確認'
    ]);
  }

  // データ保存処理
  public function store(Request $request)
  {
    // セッションからデータを取得
    $data = $request->session()->get('registration_data');

    if (!$data) {
      return redirect()->route('cui-registration')->with('error', 'セッションが切れました。もう一度入力してください。');
    }

    // データベースに保存
    $clinicUser = new ClinicUserModel();
    $clinicUser->fill($data);
    $clinicUser->save();

    // セッションをクリア
    $request->session()->forget('registration_data');

    return view('registration-done', [
      'page_title' => '利用者登録完了',
      'message' => '利用者情報が正常に登録されました。',
      'home_route' => 'cui-home',
      'list_route' => null // 一覧ページがあれば指定
    ]);
  }
}
