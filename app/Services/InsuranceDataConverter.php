<?php
//-- app/Services/InsuranceDataConverter.php --//


namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * 保険情報のデータ変換サービス
 * 保険種別などの文字列をIDに変換する共通処理を提供
 */
class InsuranceDataConverter
{
  /**
   * 保険情報の文字列データをID形式に変換
   *
   * @param array $validated バリデーション済みデータ
   * @param array $baseData 基本データ（clinic_user_id, insurers_idなど）
   * @return array ID変換後のデータ
   */
  public function convertToIds(array $validated, array $baseData = []): array
  {
    $saveData = array_merge([
      'insured_number' => $validated['insured_number'],
      'code_number' => $validated['code_number'] ?? null,
      'account_number' => $validated['account_number'] ?? null,
      'locality_code' => $validated['locality_code'] ?? null,
      'recipient_code' => $validated['recipient_code'] ?? null,
      'license_acquisition_date' => $validated['license_acquisition_date'] ?? null,
      'certification_date' => $validated['certification_date'] ?? null,
      'issue_date' => $validated['issue_date'] ?? null,
      'expiry_date' => $validated['expiry_date'] ?? null,
      'is_redeemed' => $validated['is_redeemed'] ?? false,
      'insured_name' => $validated['insured_name'] ?? null,
      'is_healthcare_subsidized' => $validated['is_healthcare_subsidized'] ?? false,
      'public_funds_payer_code' => $validated['public_funds_payer_code'] ?? null,
      'public_funds_recipient_code' => $validated['public_funds_recipient_code'] ?? null,
      'locality_code_family' => $validated['locality_code_family'] ?? null,
      'recipient_code_family' => $validated['recipient_code_family'] ?? null,
    ], $baseData);

    // 保険種別1をIDに変換
    if (isset($validated['insurance_type_1'])) {
      $type1 = DB::table('insurance_types_1')
        ->where('insurance_type_1', $validated['insurance_type_1'])
        ->first();
      $saveData['insurance_type_1_id'] = $type1 ? $type1->id : null;
    }

    // 保険種別2をIDに変換
    if (isset($validated['insurance_type_2'])) {
      $type2 = DB::table('insurance_types_2')
        ->where('insurance_type_2', $validated['insurance_type_2'])
        ->first();
      $saveData['insurance_type_2_id'] = $type2 ? $type2->id : null;
    }

    // 保険種別3をIDに変換
    if (isset($validated['insurance_type_3'])) {
      $type3 = DB::table('insurance_types_3')
        ->where('insurance_type_3', $validated['insurance_type_3'])
        ->first();
      $saveData['insurance_type_3_id'] = $type3 ? $type3->id : null;
    }

    // 被保険者区分（本人・家族）をIDに変換
    if (isset($validated['insured_person_type'])) {
      $selfFamily = DB::table('self_or_family')
        ->where('subject_type', $validated['insured_person_type'])
        ->first();
      $saveData['self_or_family_id'] = $selfFamily ? $selfFamily->id : null;
    }

    // 利用者との続柄をIDに変換
    if (isset($validated['relationship_with_clinic_user'])) {
      $rel = DB::table('relationships_with_clinic_user')
        ->where('relationship', $validated['relationship_with_clinic_user'])
        ->first();
      $saveData['relationship_with_clinic_user_id'] = $rel ? $rel->id : null;
    }

    // 一部負担金の割合をIDに変換
    if (isset($validated['expenses_borne_ratio'])) {
      $ratio = DB::table('expenses_borne_ratios')
        ->where('expenses_borne_ratio', $validated['expenses_borne_ratio'])
        ->first();
      $saveData['expenses_borne_ratio_id'] = $ratio ? $ratio->id : null;
    }

    return $saveData;
  }
}
