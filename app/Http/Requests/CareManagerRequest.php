<?php
//-- app/Http/Requests/CareManagerRequest.php --//


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CareManagerRequest extends FormRequest
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
      'care_manager_name' => 'required|max:255',
      'furigana' => 'nullable|max:255',
      'service_provider_name' => 'nullable|max:255',
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
  }

  /**
   * カスタムエラーメッセージ
   */
  public function messages(): array
  {
    return [
      'care_manager_name.required' => 'ケアマネ氏名は必須です。',
      'care_manager_name.max' => 'ケアマネ氏名は255文字以内で入力してください。',
      'email.email' => '正しいメールアドレス形式で入力してください。',
    ];
  }
}
