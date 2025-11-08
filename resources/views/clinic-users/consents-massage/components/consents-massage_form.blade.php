{{-- resources/views/clinic-users/consents-massage/components/consents-massage_form.blade.php --}}

<div class="consenting-form">
  @csrf

  <div class="mb-3">
    <label class="fw-semibold" for="consenting_doctor_id">同意医師名</label><br>
    <select id="consenting_doctor_id" name="consenting_doctor_id">
      <option value="">----</option>
      @foreach($doctors ?? [] as $doctor)
        <option value="{{ $doctor->id }}" {{ old('consenting_doctor_id', $history?->consenting_doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
          {{ $doctor->name }}
        </option>
      @endforeach
    </select>
    @error('consenting_doctor_id')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="consenting_date">同意日</label><br>
    <input type="date" id="consenting_date" name="consenting_date" value="{{ old('consenting_date', $history?->consenting_date?->format('Y-m-d') ?? '') }}">
    @error('consenting_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="consenting_start_date">同意開始年月日</label><br>
    <input type="date" id="consenting_start_date" name="consenting_start_date" value="{{ old('consenting_start_date', $history?->consenting_start_date?->format('Y-m-d') ?? '') }}">
    @error('consenting_start_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="consenting_end_date">同意終了年月日</label><br>
    <input type="date" id="consenting_end_date" name="consenting_end_date" value="{{ old('consenting_end_date', $history?->consenting_end_date?->format('Y-m-d') ?? '') }}">
    @error('consenting_end_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="benefit_period_start_date">支給期間 開始</label><br>
    <input type="date" id="benefit_period_start_date" name="benefit_period_start_date" value="{{ old('benefit_period_start_date', $history?->benefit_period_start_date?->format('Y-m-d') ?? '') }}">
    @error('benefit_period_start_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="benefit_period_end_date">支給期間 終了</label><br>
    <input type="date" id="benefit_period_end_date" name="benefit_period_end_date" value="{{ old('benefit_period_end_date', $history?->benefit_period_end_date?->format('Y-m-d') ?? '') }}">
    @error('benefit_period_end_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="first_care_date">初療年月日</label><br>
    <input type="date" id="first_care_date" name="first_care_date" value="{{ old('first_care_date', $history?->first_care_date?->format('Y-m-d') ?? '') }}">
    @error('first_care_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="disease_name_id">傷病名（あんま・マッサージ）</label><br>
    <select id="disease_name_id" name="disease_name_id">
      <option value="">----</option>
      @foreach($diseaseNames ?? [] as $disease)
        <option value="{{ $disease->id }}" {{ old('disease_name_id', $history?->disease_name_id ?? '') == $disease->id ? 'selected' : '' }}>
          {{ $disease->name }}
        </option>
      @endforeach
    </select>
    <div class="mt-1">
      <small>上記欄に記入無い場合、下記に入力する文字でマスターとして登録。</small>
    </div>
    <input type="text" id="disease_name_custom" name="disease_name_custom" placeholder="入力されたデータをマスターとして新規登録。" value="{{ old('disease_name_custom', '') }}">
    @error('disease_name_id')
      <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('disease_name_custom')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="reconsenting_expiry">再同意有効期限</label><br>
    <input type="date" id="reconsenting_expiry" name="reconsenting_expiry" value="{{ old('reconsenting_expiry', $history?->reconsenting_expiry?->format('Y-m-d') ?? '') }}">
    @error('reconsenting_expiry')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="billing_category_id">請求区分</label><br>
    <select id="billing_category_id" name="billing_category_id">
      <option value="">----</option>
      @foreach($billingCategories ?? [] as $category)
        <option value="{{ $category->id }}" {{ old('billing_category_id', $history?->billing_category_id ?? '') == $category->id ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
      @endforeach
    </select>
    @error('billing_category_id')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="outcome_id">転帰</label><br>
    <select id="outcome_id" name="outcome_id">
      <option value="">----</option>
      @foreach($outcomes ?? [] as $outcome)
        <option value="{{ $outcome->id }}" {{ old('outcome_id', $history?->outcome_id ?? '') == $outcome->id ? 'selected' : '' }}>
          {{ $outcome->name }}
        </option>
      @endforeach
    </select>
    @error('outcome_id')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold">症状1</label><br>
    <div>
      <input type="checkbox" id="symptom1_muscle_paralysis" name="symptom1[]" value="筋麻痺" {{ (is_array(old('symptom1', $history?->symptom1 ?? [])) && in_array('筋麻痺', old('symptom1', $history?->symptom1 ?? []))) ? 'checked' : '' }}>
      <label for="symptom1_muscle_paralysis">筋麻痺・筋萎縮</label>

      <input type="checkbox" id="symptom1_joint_contracture" name="symptom1[]" value="関節拘縮" {{ (is_array(old('symptom1', $history?->symptom1 ?? [])) && in_array('関節拘縮', old('symptom1', $history?->symptom1 ?? []))) ? 'checked' : '' }}>
      <label for="symptom1_joint_contracture">関節拘縮</label>

      <input type="checkbox" id="symptom1_left_upper" name="symptom1[]" value="左上肢" {{ (is_array(old('symptom1', $history?->symptom1 ?? [])) && in_array('左上肢', old('symptom1', $history?->symptom1 ?? []))) ? 'checked' : '' }}>
      <label for="symptom1_left_upper">左上肢</label>

      <input type="checkbox" id="symptom1_right_upper" name="symptom1[]" value="右上肢" {{ (is_array(old('symptom1', $history?->symptom1 ?? [])) && in_array('右上肢', old('symptom1', $history?->symptom1 ?? []))) ? 'checked' : '' }}>
      <label for="symptom1_right_upper">右上肢</label>

      <input type="checkbox" id="symptom1_left_lower" name="symptom1[]" value="左下肢" {{ (is_array(old('symptom1', $history?->symptom1 ?? [])) && in_array('左下肢', old('symptom1', $history?->symptom1 ?? []))) ? 'checked' : '' }}>
      <label for="symptom1_left_lower">左下肢</label>

      <input type="checkbox" id="symptom1_right_lower" name="symptom1[]" value="右下肢" {{ (is_array(old('symptom1', $history?->symptom1 ?? [])) && in_array('右下肢', old('symptom1', $history?->symptom1 ?? []))) ? 'checked' : '' }}>
      <label for="symptom1_right_lower">右下肢</label>
    </div>
    @error('symptom1')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold">症状2</label><br>
    <div>
      <input type="checkbox" id="symptom2_joint_disorder" name="symptom2_joint_disorder" value="1" {{ old('symptom2_joint_disorder', $history?->symptom2_joint_disorder ?? false) ? 'checked' : '' }}>
      <label for="symptom2_joint_disorder">関節拘縮</label>
    </div>
    <div class="mt-2">
      <input type="checkbox" id="symptom2_right_shoulder" name="symptom2[]" value="右肩" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('右肩', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_right_shoulder">右肩</label>

      <input type="checkbox" id="symptom2_right_elbow" name="symptom2[]" value="右肘" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('右肘', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_right_elbow">右肘</label>

      <input type="checkbox" id="symptom2_right_hand" name="symptom2[]" value="右手" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('右手', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_right_hand">右手</label>

      <input type="checkbox" id="symptom2_right_related_joint" name="symptom2[]" value="右関節周囲" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('右関節周囲', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_right_related_joint">右関節周囲</label>

      <input type="checkbox" id="symptom2_right_hip" name="symptom2[]" value="右腰" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('右腰', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_right_hip">右腰</label>

      <input type="checkbox" id="symptom2_right_knee" name="symptom2[]" value="右膝" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('右膝', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_right_knee">右膝</label>
    </div>
    <div class="mt-2">
      <input type="checkbox" id="symptom2_left_shoulder" name="symptom2[]" value="左肩" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('左肩', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_left_shoulder">左肩</label>

      <input type="checkbox" id="symptom2_left_elbow" name="symptom2[]" value="左肘" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('左肘', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_left_elbow">左肘</label>

      <input type="checkbox" id="symptom2_left_hand" name="symptom2[]" value="左手" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('左手', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_left_hand">左手</label>

      <input type="checkbox" id="symptom2_left_related_joint" name="symptom2[]" value="左関節周囲" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('左関節周囲', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_left_related_joint">左関節周囲</label>

      <input type="checkbox" id="symptom2_left_hip" name="symptom2[]" value="左腰" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('左腰', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_left_hip">左腰</label>

      <input type="checkbox" id="symptom2_left_knee" name="symptom2[]" value="左膝" {{ (is_array(old('symptom2', $history?->symptom2 ?? [])) && in_array('左膝', old('symptom2', $history?->symptom2 ?? []))) ? 'checked' : '' }}>
      <label for="symptom2_left_knee">左膝</label>
    </div>
    <div class="mt-2">
      <input type="checkbox" id="symptom2_other" name="symptom2_other" value="1" {{ old('symptom2_other', $history?->symptom2_other ?? false) ? 'checked' : '' }}>
      <label for="symptom2_other">その他（</label>
      <input type="text" id="symptom2_other_text" name="symptom2_other_text" value="{{ old('symptom2_other_text', $history?->symptom2_other_text ?? '') }}" style="width: 200px;">
      <label>）</label>
    </div>
    @error('symptom2')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="symptom3">症状3</label><br>
    <div>
      <input type="checkbox" id="symptom3_other" name="symptom3_other" value="1" {{ old('symptom3_other', $history?->symptom3_other ?? false) ? 'checked' : '' }}>
      <label for="symptom3_other">その他</label>
    </div>
    <div class="mt-1">
      <small>（筋麻痺、筋萎縮又は関節拘縮のある名称部位以外の部位に施術を必要とする場合には記載下さい）</small>
    </div>
    <textarea id="symptom3" name="symptom3" rows="3">{{ old('symptom3', $history?->symptom3 ?? '') }}</textarea>
    @error('symptom3')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold">施術の種類1</label><br>
    <div>
      <input type="checkbox" id="treatment_type1_massage" name="treatment_type1[]" value="マッサージ" {{ (is_array(old('treatment_type1', $history?->treatment_type1 ?? [])) && in_array('マッサージ', old('treatment_type1', $history?->treatment_type1 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type1_massage">マッサージ</label>

      <input type="checkbox" id="treatment_type1_muscle_paralysis" name="treatment_type1[]" value="筋麻痺" {{ (is_array(old('treatment_type1', $history?->treatment_type1 ?? [])) && in_array('筋麻痺', old('treatment_type1', $history?->treatment_type1 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type1_muscle_paralysis">筋麻痺</label>

      <input type="checkbox" id="treatment_type1_left_upper" name="treatment_type1[]" value="左上肢" {{ (is_array(old('treatment_type1', $history?->treatment_type1 ?? [])) && in_array('左上肢', old('treatment_type1', $history?->treatment_type1 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type1_left_upper">左上肢</label>

      <input type="checkbox" id="treatment_type1_left_lower" name="treatment_type1[]" value="左下肢" {{ (is_array(old('treatment_type1', $history?->treatment_type1 ?? [])) && in_array('左下肢', old('treatment_type1', $history?->treatment_type1 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type1_left_lower">左下肢</label>

      <input type="checkbox" id="treatment_type1_right_upper" name="treatment_type1[]" value="右上肢" {{ (is_array(old('treatment_type1', $history?->treatment_type1 ?? [])) && in_array('右上肢', old('treatment_type1', $history?->treatment_type1 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type1_right_upper">右上肢</label>

      <input type="checkbox" id="treatment_type1_right_lower" name="treatment_type1[]" value="右下肢" {{ (is_array(old('treatment_type1', $history?->treatment_type1 ?? [])) && in_array('右下肢', old('treatment_type1', $history?->treatment_type1 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type1_right_lower">右下肢</label>
    </div>
    @error('treatment_type1')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold">施術の種類2</label><br>
    <div>
      <input type="checkbox" id="treatment_type2_corrective_hand" name="treatment_type2_corrective_hand" value="1" {{ old('treatment_type2_corrective_hand', $history?->treatment_type2_corrective_hand ?? false) ? 'checked' : '' }}>
      <label for="treatment_type2_corrective_hand">変形徒手矯正術</label>
    </div>
    <div class="mt-2">
      <input type="checkbox" id="treatment_type2_right_upper" name="treatment_type2[]" value="右上肢" {{ (is_array(old('treatment_type2', $history?->treatment_type2 ?? [])) && in_array('右上肢', old('treatment_type2', $history?->treatment_type2 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type2_right_upper">右上肢</label>

      <input type="checkbox" id="treatment_type2_left_upper" name="treatment_type2[]" value="左上肢" {{ (is_array(old('treatment_type2', $history?->treatment_type2 ?? [])) && in_array('左上肢', old('treatment_type2', $history?->treatment_type2 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type2_left_upper">左上肢</label>

      <input type="checkbox" id="treatment_type2_right_lower" name="treatment_type2[]" value="右下肢" {{ (is_array(old('treatment_type2', $history?->treatment_type2 ?? [])) && in_array('右下肢', old('treatment_type2', $history?->treatment_type2 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type2_right_lower">右下肢</label>

      <input type="checkbox" id="treatment_type2_left_lower" name="treatment_type2[]" value="左下肢" {{ (is_array(old('treatment_type2', $history?->treatment_type2 ?? [])) && in_array('左下肢', old('treatment_type2', $history?->treatment_type2 ?? []))) ? 'checked' : '' }}>
      <label for="treatment_type2_left_lower">左下肢</label>
    </div>
    @error('treatment_type2')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold">往療の必要有無</label><br>
    <div>
      <input type="radio" id="house_visit_necessary" name="house_visit_necessity" value="必要とする" {{ old('house_visit_necessity', $history?->house_visit_necessity ?? '') == '必要とする' ? 'checked' : '' }}>
      <label for="house_visit_necessary">必要とする</label>

      <input type="radio" id="house_visit_not_necessary" name="house_visit_necessity" value="必要としない" {{ old('house_visit_necessity', $history?->house_visit_necessity ?? '') == '必要としない' ? 'checked' : '' }}>
      <label for="house_visit_not_necessary">必要としない</label>
    </div>
    <div class="mt-2">
      <label class="fw-semibold" for="house_visit_reason_id">往療を必要とする理由</label><br>
      <select id="house_visit_reason_id" name="house_visit_reason_id">
        <option value="">----</option>
        @foreach($houseVisitReasons ?? [] as $reason)
          <option value="{{ $reason->id }}" {{ old('house_visit_reason_id', $history?->house_visit_reason_id ?? '') == $reason->id ? 'selected' : '' }}>
            {{ $reason->name }}
          </option>
        @endforeach
      </select>
      <div class="mt-1">
        <small>↑「その他」を選択した場合はご入力（</small>
        <input type="text" id="house_visit_reason_custom" name="house_visit_reason_custom" value="{{ old('house_visit_reason_custom', $history?->house_visit_reason_custom ?? '') }}" style="width: 200px;">
        <small>）</small>
      </div>
    </div>
    @error('house_visit_necessity')
      <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('house_visit_reason_id')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="nursing_care_level">介護保険の要介護度</label><br>
    <input type="text" id="nursing_care_level" name="nursing_care_level" value="{{ old('nursing_care_level', $history?->nursing_care_level ?? '') }}">
    @error('nursing_care_level')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="notes">注意事項等</label><br>
    <textarea id="notes" name="notes" rows="3">{{ old('notes', $history?->notes ?? '') }}</textarea>
    <div class="mt-1">
      <small>施術に当たって注意を要する事項等があれば記載下さい（任意）</small>
    </div>
    @error('notes')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="addition_removal_period">要加除期間</label><br>
    <input type="text" id="addition_removal_period" name="addition_removal_period" value="{{ old('addition_removal_period', $history?->addition_removal_period ?? '') }}">
    @error('addition_removal_period')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="initial_treatment_id">初回施術内容</label><br>
    <select id="initial_treatment_id" name="initial_treatment_id">
      <option value="">----</option>
      @foreach($initialTreatments ?? [] as $treatment)
        <option value="{{ $treatment->id }}" {{ old('initial_treatment_id', $history?->initial_treatment_id ?? '') == $treatment->id ? 'selected' : '' }}>
          {{ $treatment->name }}
        </option>
      @endforeach
    </select>
    @error('initial_treatment_id')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="disease_progress_id">発病負傷経過</label><br>
    <select id="disease_progress_id" name="disease_progress_id">
      <option value="">----</option>
      @foreach($diseaseProgresses ?? [] as $progress)
        <option value="{{ $progress->id }}" {{ old('disease_progress_id', $history?->disease_progress_id ?? '') == $progress->id ? 'selected' : '' }}>
          {{ $progress->name }}
        </option>
      @endforeach
    </select>
    <div class="mt-1">
      <small>上記欄に記入無い場合、下記に入力する文字でマスターとして登録。</small>
    </div>
    <input type="text" id="disease_progress_custom" name="disease_progress_custom" placeholder="入力されたデータをマスターとして新規登録。" value="{{ old('disease_progress_custom', '') }}">
    @error('disease_progress_id')
      <div class="text-danger">{{ $message }}</div>
    @enderror
    @error('disease_progress_custom')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="work_related_id">業務上外等区分</label><br>
    <select id="work_related_id" name="work_related_id">
      <option value="">----</option>
      @foreach($workRelatedCategories ?? [] as $category)
        <option value="{{ $category->id }}" {{ old('work_related_id', $history?->work_related_id ?? '') == $category->id ? 'selected' : '' }}>
          {{ $category->name }}
        </option>
      @endforeach
    </select>
    @error('work_related_id')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="onset_and_injury_date">発病 負傷年月日</label><br>
    <input type="date" id="onset_and_injury_date" name="onset_and_injury_date" value="{{ old('onset_and_injury_date', $history?->onset_and_injury_date?->format('Y-m-d') ?? '') }}">
    @error('onset_and_injury_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <button type="submit">{{ $submitLabel }}</button>
    <a href="{{ $cancelRoute }}">
    <button type="button">キャンセル</button>
    </a>
  </div>
</div>
