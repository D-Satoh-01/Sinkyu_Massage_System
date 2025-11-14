<?php
//-- app/Services/ConsentDataConverter.php --//

namespace App\Services;

use App\Models\Illness;
use App\Models\BillCategory;
use App\Models\Outcome;
use App\Models\HousecallReason;
use App\Models\TherapyContent;
use App\Models\Condition;
use App\Models\WorkScopeType;

/**
 * 同意医師履歴データ変換サービス
 * 
 * マスターデータのID→名称変換と表示用ラベルを提供する。
 * 確認画面でIDではなく名称を表示する際に使用。
 */
class ConsentDataConverter
{
    /**
     * マスターデータIDを名称に変換
     * 
     * フォームで選択されたマスターデータのIDを実際の名称に変換し、
     * 確認画面で表示できる形式にする。
     *
     * @param array $data フォームデータ
     * @return array 変換後のデータ
     */
    public function convertIdsToNames(array $data): array
    {
        $result = $data;

        $mappings = [
            'injury_and_illness_name_id' => [Illness::class, 'illness_name'],
            'bill_category_id' => [BillCategory::class, 'bill_category'],
            'outcome_id' => [Outcome::class, 'outcome'],
            'housecall_reason_id' => [HousecallReason::class, 'housecall_reason'],
            'first_therapy_content_id' => [TherapyContent::class, 'therapy_content'],
            'condition_id' => [Condition::class, 'condition_name'],
            'work_scope_type_id' => [WorkScopeType::class, 'work_scope_type'],
        ];

        foreach ($mappings as $field => [$model, $attribute]) {
            if (isset($data[$field]) && $data[$field]) {
                $record = $model::find($data[$field]);
                $result[$field] = $record ? $record->$attribute : '';
            }
        }

        return $result;
    }

    /**
     * 同意医師履歴のフィールドラベルを取得
     * 
     * 確認画面や一覧画面で使用するフィールドの日本語ラベルを返す。
     *
     * @return array フィールド名 => ラベル名の配列
     */
    public function getLabels(): array
    {
        return [
            'consenting_doctor_name' => '同意医師名',
            'consenting_date' => '同意日',
            'consenting_start_date' => '同意開始年月日',
            'consenting_end_date' => '同意終了年月日',
            'benefit_period_start_date' => '支給期間 開始',
            'benefit_period_end_date' => '支給期間 終了',
            'first_care_date' => '初療年月日',
            'injury_and_illness_name_id' => '傷病名（あんま・マッサージ）',
            'disease_name_custom' => '傷病名（新規登録）',
            'reconsenting_expiry' => '再同意有効期限',
            'bill_category_id' => '請求区分',
            'outcome_id' => '転帰',
            'symptom1' => '症状1',
            'symptom2_joint_disorder' => '症状2（関節拘縮）',
            'symptom2' => '症状2（部位）',
            'symptom2_other' => '症状2（その他）',
            'symptom2_other_text' => '症状2（その他詳細）',
            'symptom3_other' => '症状3（その他）',
            'symptom3' => '症状3（詳細）',
            'treatment_type1' => '施術の種類1',
            'treatment_type2_corrective_hand' => '施術の種類2（変形徒手矯正術）',
            'treatment_type2' => '施術の種類2（部位）',
            'is_housecall_required' => '往療の必要有無',
            'housecall_reason_id' => '往療を必要とする理由',
            'housecall_reason_addendum' => '往療理由（その他詳細）',
            'care_level' => '介護保険の要介護度',
            'notes' => '注意事項等',
            'therapy_period' => '要加除期間',
            'first_therapy_content_id' => '初回施術内容',
            'condition_id' => '発病負傷経過',
            'disease_progress_custom' => '発病負傷経過（新規登録）',
            'work_scope_type_id' => '業務上外等区分',
            'onset_and_injury_date' => '発病 負傷年月日'
        ];
    }
}
