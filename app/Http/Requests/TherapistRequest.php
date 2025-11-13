<?php
//-- app/Http/Requests/TherapistRequest.php --//


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TherapistRequest extends FormRequest
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
      'therapist_name' => 'required|max:255',
      'furigana' => 'nullable|max:255',
      'postal_code' => 'nullable|max:8',
      'address_1' => 'nullable|max:255',
      'address_2' => 'nullable|max:255',
      'address_3' => 'nullable|max:255',
      'phone' => 'nullable|max:255',
      'cell_phone' => 'nullable|max:255',
      'fax' => 'nullable|max:255',
      'email' => 'nullable|email|max:255',
      'license_hari_id' => 'nullable|integer',
      'license_hari_number' => 'nullable|integer',
      'license_hari_issued_date' => 'nullable|date',
      'license_kyu_id' => 'nullable|integer',
      'license_kyu_number' => 'nullable|integer',
      'license_kyu_issued_date' => 'nullable|date',
      'license_massage_id' => 'nullable|integer',
      'license_massage_number' => 'nullable|integer',
      'license_massage_issued_date' => 'nullable|date',
      'member_number' => 'nullable|integer',
      'note' => 'nullable|max:255',
    ];
  }

  /**
   * カスタムエラーメッセージ
   */
  public function messages(): array
  {
    return [
      'therapist_name.required' => '施術者名は必須です。',
      'therapist_name.max' => '施術者名は255文字以内で入力してください。',
      'email.email' => '正しいメールアドレス形式で入力してください。',
    ];
  }
}
