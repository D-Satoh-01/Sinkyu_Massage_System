<?php
//-- app/Http/Requests/DoctorRequest.php --//


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
      'last_name' => 'required|max:255',
      'first_name' => 'nullable|max:255',
      'last_name_kana' => 'nullable|max:255',
      'first_name_kana' => 'nullable|max:255',
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
  }

  /**
   * カスタムエラーメッセージ
   */
  public function messages(): array
  {
    return [
      'last_name.required' => '姓は必須です。',
      'last_name.max' => '姓は255文字以内で入力してください。',
      'first_name.max' => '名は255文字以内で入力してください。',
      'last_name_kana.max' => 'セイは255文字以内で入力してください。',
      'first_name_kana.max' => 'メイは255文字以内で入力してください。',
      'medical_institutions_id.exists' => '選択された医療機関が存在しません。',
      'email.email' => '正しいメールアドレス形式で入力してください。',
    ];
  }
}
