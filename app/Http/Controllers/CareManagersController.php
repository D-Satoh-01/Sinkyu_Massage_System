<?php
//-- app/Http/Controllers/CareManagersController.php --//


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CareManagerRequest;

class CareManagersController extends Controller
{
  // ケアマネ情報一覧表示
  public function index()
  {
    // DataTablesを使用するため、全件取得
    $careManagers = DB::table('caremanagers')
      ->orderBy('id', 'desc')
      ->get();

    return view('caremanagers.caremanagers_index', compact('careManagers'));
  }

  // ケアマネ情報新規登録画面表示
  public function create()
  {
    // セッションに保存されたデータがあれば、それをフラッシュデータとして設定
    $sessionData = session()->get('caremanagers_registration_data');
    if ($sessionData) {
      request()->merge($sessionData);
      session()->flashInput($sessionData);
      // セッションデータを保持（確認画面に戻った場合にも利用できるように）
      session()->put('caremanagers_registration_data', $sessionData);
    }

    return view('caremanagers.caremanagers_registration', [
      'mode' => 'create',
      'title' => 'ケアマネ情報新規登録',
      'careManager' => null
    ]);
  }

  // ケアマネ情報新規登録：確認画面の表示
  public function confirm(CareManagerRequest $request)
  {
    $validated = $request->validated();

    // セッションに保存
    $request->session()->put('caremanagers_registration_data', $validated);

    // 確認画面のラベル設定
    $labels = $this->getCareManagerLabels();

    return view('registration-review', [
      'data' => $validated,
      'labels' => $labels,
      'back_route' => 'caremanagers.create',
      'store_route' => 'caremanagers.store',
      'page_title' => 'ケアマネ情報登録内容確認',
      'registration_message' => 'ケアマネ情報の登録を行います。',
    ]);
  }

  // ケアマネ情報新規登録処理
  public function store(Request $request)
  {
    // セッションからデータを取得
    $data = $request->session()->get('caremanagers_registration_data');

    if (!$data) {
      return redirect()->route('caremanagers.create')->with('error', 'セッションが切れました。もう一度入力してください。');
    }

    // データ挿入
    DB::table('caremanagers')->insert([
      'care_manager_name' => $data['care_manager_name'],
      'furigana' => $data['furigana'] ?? null,
      'service_provider_name' => $data['service_provider_name'] ?? null,
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
    $request->session()->forget('caremanagers_registration_data');

    return redirect()->route('caremanagers.index')->with('success', 'ケアマネ情報を登録しました。');
  }

  // ケアマネ情報編集画面表示
  public function edit($id)
  {
    // セッションに保存されたデータがあれば、それをフラッシュデータとして設定
    $sessionData = session()->get('caremanagers_edit_data');
    $sessionId = session()->get('caremanagers_edit_id');
    if ($sessionData && $sessionId == $id) {
      request()->merge($sessionData);
      session()->flashInput($sessionData);
      // セッションデータを保持（確認画面に戻った場合にも利用できるように）
      session()->put('caremanagers_edit_data', $sessionData);
      session()->put('caremanagers_edit_id', $sessionId);
    }

    $careManager = DB::table('caremanagers')->where('id', $id)->first();

    if (!$careManager) {
      return redirect()->route('caremanagers.index')->with('error', 'ケアマネ情報が見つかりません。');
    }

    return view('caremanagers.caremanagers_registration', [
      'mode' => 'edit',
      'title' => 'ケアマネ情報編集',
      'careManager' => $careManager
    ]);
  }

  // ケアマネ情報編集：確認画面の表示
  public function editConfirm(CareManagerRequest $request, $id)
  {
    $validated = $request->validated();

    // セッションに保存
    $request->session()->put('caremanagers_edit_data', $validated);
    $request->session()->put('caremanagers_edit_id', $id);

    // 確認画面のラベル設定
    $labels = $this->getCareManagerLabels();

    return view('registration-review', [
      'data' => $validated,
      'labels' => $labels,
      'back_route' => 'caremanagers.edit',
      'back_id' => $id,
      'store_route' => 'caremanagers.update',
      'page_title' => 'ケアマネ情報編集内容確認',
      'registration_message' => 'ケアマネ情報の更新を行います。',
    ]);
  }

  // ケアマネ情報更新処理
  public function update(Request $request, $id)
  {
    $data = $request->session()->get('caremanagers_edit_data');
    $sessionId = $request->session()->get('caremanagers_edit_id');

    if (!$data || $sessionId != $id) {
      return redirect()->route('caremanagers.edit', $id)->with('error', 'セッションが切れました。もう一度入力してください。');
    }

    // データ更新
    DB::table('caremanagers')->where('id', $id)->update([
      'care_manager_name' => $data['care_manager_name'],
      'furigana' => $data['furigana'] ?? null,
      'service_provider_name' => $data['service_provider_name'] ?? null,
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
    $request->session()->forget('caremanagers_edit_data');
    $request->session()->forget('caremanagers_edit_id');

    return redirect()->route('caremanagers.index')->with('success', 'ケアマネ情報を更新しました。');
  }

  // ケアマネ情報のラベル取得
  private function getCareManagerLabels()
  {
    return [
      'care_manager_name' => 'ケアマネ氏名',
      'furigana' => 'フリガナ',
      'service_provider_name' => 'サービス事業者名',
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

  // ケアマネ情報削除
  public function destroy($id)
  {
    DB::table('caremanagers')->where('id', $id)->delete();
    return redirect()->route('caremanagers.index')->with('success', 'ケアマネ情報を削除しました。');
  }
}
