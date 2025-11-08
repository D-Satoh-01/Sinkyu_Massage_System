<?php
//-- app/Http/Requests/ConsentingDoctorHistoryMassageRequest.php --//


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsentingDoctorHistoryMassageRequest extends FormRequest
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
    ];
  }

  /**
   * カスタムエラーメッセージ
   */
  public function messages(): array
  {
    return [
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
    ];
  }
}
