<?php
//-- app/Http/Requests/ClinicUserRequest.php --//


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClinicUserRequest extends FormRequest
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
    return [
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
    ];
  }

  /**
   * バリデーション前の処理（チェックボックスの変換）
   */
  protected function prepareForValidation(): void
  {
    $this->merge([
      'is_redeemed' => $this->has('is_redeemed')
    ]);
  }
}
