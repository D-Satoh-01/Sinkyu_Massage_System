<?php
//-- app/Http/Requests/InsuranceRequest.php --//


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsuranceRequest extends FormRequest
{
  /**
   * リクエストが許可されているか判定
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * バリデーションルールを取得
   */
  public function rules(): array
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
    if (!$this->filled('selected_insurer')) {
      $rules['new_insurer_number'] = 'required|string|regex:/^\d{6}(\d{2})?$/';
    }

    return $rules;
  }

  /**
   * カスタムエラーメッセージ
   */
  public function messages(): array
  {
    return [
      'new_insurer_number.required' => '保険者番号を入力してください。',
      'new_insurer_number.regex' => '保険者番号は6桁または8桁の数字を入力してください。',
    ];
  }

  /**
   * バリデーション前の処理（チェックボックスの変換）
   */
  protected function prepareForValidation(): void
  {
    $this->merge([
      'is_redeemed' => $this->has('is_redeemed'),
      'is_healthcare_subsidized' => $this->has('is_healthcare_subsidized')
    ]);
  }
}
