<?php
//-- app/Services/InsurerService.php --//


namespace App\Services;

use App\Models\Insurer;

/**
 * 保険者（Insurer）関連の共通処理を提供するサービス
 */
class InsurerService
{
  /**
   * バリデーション済みデータから保険者IDを取得または新規作成
   *
   * @param array $validated バリデーション済みデータ
   * @param int|null $defaultInsurerId デフォルトの保険者ID（編集時に使用）
   * @return int|null 保険者ID
   */
  public function getOrCreateInsurerId(array $validated, ?int $defaultInsurerId = null): ?int
  {
    // 既存の保険者が選択されている場合
    if (isset($validated['selected_insurer']) && $validated['selected_insurer']) {
      return $validated['selected_insurer'];
    }

    // 新規保険者番号が入力されている場合、新規作成
    if (isset($validated['new_insurer_number']) && $validated['new_insurer_number']) {
      $newInsurer = Insurer::create([
        'insurer_number' => $validated['new_insurer_number'],
        'insurer_name' => $validated['new_insurer_name'] ?? null,
        'postal_code' => $validated['new_postal_code'] ?? null,
        'address' => $validated['new_address'] ?? null,
        'recipient_name' => $validated['new_recipient_name'] ?? null
      ]);
      return $newInsurer->id;
    }

    // どちらも該当しない場合はデフォルト値を返す
    return $defaultInsurerId;
  }
}
