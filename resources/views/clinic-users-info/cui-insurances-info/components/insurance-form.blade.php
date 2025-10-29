{{-- resources/views/clinic-users-info/cui-insurances-info/components/insurance-form.blade.php --}}

<div class="insurance-form">
  @csrf
  @if($isEdit)
  @method('PUT')
  @endif

  <div class="mb-3">
  <label>保険種別１</label><br>
  @php
    $type1Map = [1 => '社･国･組', 2 => '公費', 3 => '後期', 4 => '退職'];
    $currentType1 = old('insurance_type_1', (isset($insurance) && $insurance && $insurance->insurance_type_1_id) ? $type1Map[$insurance->insurance_type_1_id] : '');
  @endphp
  <input type="radio" id="insurance_type_1_company_national_union" name="insurance_type_1" value="社･国･組" {{ $currentType1 == '社･国･組' ? 'checked' : '' }}>
  <label for="insurance_type_1_company_national_union" class="me-3">社･国･組</label>
  <input type="radio" id="insurance_type_1_public_expense" name="insurance_type_1" value="公費" {{ $currentType1 == '公費' ? 'checked' : '' }}>
  <label for="insurance_type_1_public_expense" class="me-3">公費</label>
  <input type="radio" id="insurance_type_1_latter_period" name="insurance_type_1" value="後期" {{ $currentType1 == '後期' ? 'checked' : '' }}>
  <label for="insurance_type_1_latter_period" class="me-3">後期</label>
  <input type="radio" id="insurance_type_1_retirement" name="insurance_type_1" value="退職" {{ $currentType1 == '退職' ? 'checked' : '' }}>
  <label for="insurance_type_1_retirement" class="me-3">退職</label>
  @error('insurance_type_1')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>

  <div class="mb-3">
  <label>保険種別２</label><br>
  @php
    $type2Map = [1 => '単独', 2 => '２併', 3 => '３併'];
    $currentType2 = old('insurance_type_2', (isset($insurance) && $insurance && $insurance->insurance_type_2_id) ? $type2Map[$insurance->insurance_type_2_id] : '');
  @endphp
  <input type="radio" id="insurance_type_2_single" name="insurance_type_2" value="単独" {{ $currentType2 == '単独' ? 'checked' : '' }}>
  <label for="insurance_type_2_single" class="me-3">単独</label>
  <input type="radio" id="insurance_type_2_dual_combination" name="insurance_type_2" value="２併" {{ $currentType2 == '２併' ? 'checked' : '' }}>
  <label for="insurance_type_2_dual_combination" class="me-3">２併</label>
  <input type="radio" id="insurance_type_2_triple_combination" name="insurance_type_2" value="３併" {{ $currentType2 == '３併' ? 'checked' : '' }}>
  <label for="insurance_type_2_triple_combination" class="me-3">３併</label>
  @error('insurance_type_2')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>

  <div class="mb-3">
  <label>保険種別３</label><br>
  @php
    $type3Map = [1 => '本外', 2 => '三外', 3 => '家外', 4 => '高外9', 5 => '高外8'];
    $currentType3 = old('insurance_type_3', (isset($insurance) && $insurance && $insurance->insurance_type_3_id) ? $type3Map[$insurance->insurance_type_3_id] : '');
  @endphp
  <input type="radio" id="insurance_type_3_main_external" name="insurance_type_3" value="本外" {{ $currentType3 == '本外' ? 'checked' : '' }}>
  <label for="insurance_type_3_main_external" class="me-3">本外</label>
  <input type="radio" id="insurance_type_3_three_external" name="insurance_type_3" value="三外" {{ $currentType3 == '三外' ? 'checked' : '' }}>
  <label for="insurance_type_3_three_external" class="me-3">三外</label>
  <input type="radio" id="insurance_type_3_home_external" name="insurance_type_3" value="家外" {{ $currentType3 == '家外' ? 'checked' : '' }}>
  <label for="insurance_type_3_home_external" class="me-3">家外</label>
  <input type="radio" id="insurance_type_3_high_external_9" name="insurance_type_3" value="高外9" {{ $currentType3 == '高外9' ? 'checked' : '' }}>
  <label for="insurance_type_3_high_external_9" class="me-3">高外9</label>
  <input type="radio" id="insurance_type_3_high_external_8" name="insurance_type_3" value="高外8" {{ $currentType3 == '高外8' ? 'checked' : '' }}>
  <label for="insurance_type_3_high_external_8" class="me-3">高外8</label>
  @error('insurance_type_3')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>

  <div class="mb-3">
  <label>本人・家族</label><br>
  @php
    $selfOrFamilyMap = [1 => '本人', 2 => '六歳', 3 => '家族', 4 => '高齢１', 5 => '高齢', 6 => '高齢７'];
    $currentSelfOrFamily = old('insured_person_type', (isset($insurance) && $insurance && $insurance->self_or_family_id) ? $selfOrFamilyMap[$insurance->self_or_family_id] : '');
  @endphp
  <input type="radio" id="insured_person_type_self" name="insured_person_type" value="本人" {{ $currentSelfOrFamily == '本人' ? 'checked' : '' }}>
  <label for="insured_person_type_self" class="me-3">本人</label>
  <input type="radio" id="insured_person_type_six_years_old" name="insured_person_type" value="六歳" {{ $currentSelfOrFamily == '六歳' ? 'checked' : '' }}>
  <label for="insured_person_type_six_years_old" class="me-3">六歳</label>
  <input type="radio" id="insured_person_type_family" name="insured_person_type" value="家族" {{ $currentSelfOrFamily == '家族' ? 'checked' : '' }}>
  <label for="insured_person_type_family" class="me-3">家族</label>
  <input type="radio" id="insured_person_type_elderly_1" name="insured_person_type" value="高齢１" {{ $currentSelfOrFamily == '高齢１' ? 'checked' : '' }}>
  <label for="insured_person_type_elderly_1" class="me-3">高齢１</label>
  <input type="radio" id="insured_person_type_elderly" name="insured_person_type" value="高齢" {{ $currentSelfOrFamily == '高齢' ? 'checked' : '' }}>
  <label for="insured_person_type_elderly" class="me-3">高齢</label>
  <input type="radio" id="insured_person_type_elderly_7" name="insured_person_type" value="高齢７" {{ $currentSelfOrFamily == '高齢７' ? 'checked' : '' }}>
  <label for="insured_person_type_elderly_7" class="me-3">高齢７</label>
  @error('insured_person_type')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>

  <div class="mb-3">
  <label for="insured_number">被保険者番号</label><br>
  <input type="text" id="insured_number" name="insured_number" value="{{ old('insured_number', ($insurance->insured_number ?? '')) }}">
  @error('insured_number')
    <div class="text-danger">{{ $message }}</div>
  @enderror
  </div>

  <div class="mb-3">
  <label for="symbol">記号</label><br>
  <input type="text" id="symbol" name="symbol" value="{{ old('symbol', ($insurance->code_number ?? '')) }}">
  </div>

  <div class="mb-3">
  <label for="number">番号</label><br>
  <input type="text" id="number" name="number" value="{{ old('number', ($insurance->account_number ?? '')) }}">
  </div>

  <div class="mb-3">
  <label for="municipal_code_before">区市町村番号</label><br>
  <input type="text" id="municipal_code_before" name="municipal_code_before" value="{{ old('municipal_code_before', ($insurance->locality_code ?? '')) }}">
  </div>

  <div class="mb-3">
  <label for="recipient_number_before">受給者番号</label><br>
  <input type="text" id="recipient_number_before" name="recipient_number_before" value="{{ old('recipient_number_before', ($insurance->recipient_code ?? '')) }}">
  </div>

  <div class="mb-3">
  <label for="qualification_date">資格取得年月日</label><br>
  <input type="date" id="qualification_date" name="qualification_date" value="{{ old('qualification_date', ($insurance && $insurance->license_acquisition_date) ? $insurance->license_acquisition_date->format('Y-m-d') : '') }}">
  </div>

  <div class="mb-3">
  <label for="certification_date">認定年月日</label><br>
  <input type="date" id="certification_date" name="certification_date" value="{{ old('certification_date', ($insurance && $insurance->certification_date) ? $insurance->certification_date->format('Y-m-d') : '') }}">
  </div>

  <div class="mb-3">
  <label for="issue_date">発行（交付）年月日</label><br>
  <input type="date" id="issue_date" name="issue_date" value="{{ old('issue_date', ($insurance && $insurance->issue_date) ? $insurance->issue_date->format('Y-m-d') : '') }}">
  </div>

  <div class="mb-3">
  <label for="copayment_rate">一部負担金の割合</label><br>
  <select id="copayment_rate" name="copayment_rate">
    <option value="">----</option>
    @php
    $copaymentMap = [1 => '1割', 2 => '2割', 3 => '3割'];
    $currentCopayment = old('copayment_rate', (isset($insurance) && $insurance && $insurance->expenses_borne_ratio_id) ? $copaymentMap[$insurance->expenses_borne_ratio_id] : '');
    @endphp
    <option value="1割" {{ $currentCopayment == '1割' ? 'selected' : '' }}>1割</option>
    <option value="2割" {{ $currentCopayment == '2割' ? 'selected' : '' }}>2割</option>
    <option value="3割" {{ $currentCopayment == '3割' ? 'selected' : '' }}>3割</option>
  </select>
  </div>

  <div class="mb-3">
  <label for="expiration_date">有効期限</label><br>
  <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', ($insurance && $insurance->expiry_date) ? $insurance->expiry_date->format('Y-m-d') : '') }}">
  </div>

  <div class="mb-3">
  <div class="checkbox-group">
    <label for="reimbursement_target">償還対象</label><br>
    <input type="checkbox" id="reimbursement_target" name="reimbursement_target" value="1" {{ old('reimbursement_target', ($insurance->is_redeemed ?? false)) ? 'checked' : '' }}>
  </div>
  </div>

  <div class="mb-3">
  <label for="insured_person_name">被保険者氏名</label><br>
  <input type="text" id="insured_person_name" name="insured_person_name" value="{{ old('insured_person_name', ($insurance->insured_name ?? '')) }}">
  </div>

  <div class="mb-3">
  <label for="relationship">利用者との続柄</label><br>
  <select id="relationship" name="relationship">
    <option value="">----</option>
    @php
    $relationshipMap = [1 => '本人', 2 => '家族'];
    $currentRelationship = old('relationship', (isset($insurance) && $insurance && $insurance->relationship_with_clinic_user_id) ? $relationshipMap[$insurance->relationship_with_clinic_user_id] : '');
    @endphp
    <option value="本人" {{ $currentRelationship == '本人' ? 'selected' : '' }}>本人</option>
    <option value="家族" {{ $currentRelationship == '家族' ? 'selected' : '' }}>家族</option>
  </select>
  </div>

  <div class="mb-3">
  <div class="checkbox-group">
    <label for="medical_assistance_target">医療助成対象</label><br>
    <input type="checkbox" id="medical_assistance_target" name="medical_assistance_target" value="1" {{ old('medical_assistance_target', ($insurance->is_healthcare_subsidized ?? false)) ? 'checked' : '' }} onchange="toggleMedicalAssistanceFields()">
  </div>
  </div>

  <div id="medical-assistance-fields" style="{{ old('medical_assistance_target', ($insurance->is_healthcare_subsidized ?? false)) ? '' : 'display: none;' }}">
  <div class="mb-3">
    <label for="public_burden_number">公費負担者番号</label><br>
    <input type="text" id="public_burden_number" name="public_burden_number" value="{{ old('public_burden_number', ($insurance->public_funds_payer_code ?? '')) }}">
  </div>

  <div class="mb-3">
    <label for="public_recipient_number">公費受給者番号</label><br>
    <input type="text" id="public_recipient_number" name="public_recipient_number" value="{{ old('public_recipient_number', ($insurance->public_funds_recipient_code ?? '')) }}">
  </div>

  <div class="mb-3">
    <label for="municipal_code_family">区市町村番号（家族）</label><br>
    <input type="text" id="municipal_code_family" name="municipal_code_family" value="{{ old('municipal_code_family', '') }}" disabled>
  </div>

  <div class="mb-3">
    <label for="recipient_number_family">受給者番号（家族）</label><br>
    <input type="text" id="recipient_number_family" name="recipient_number_family" value="{{ old('recipient_number_family', '') }}" disabled>
  </div>
  </div>

  @if(!$isEdit)
  <div class="mb-3">
    <label for="selected_insurer">保険者情報</label><br>
    <select id="selected_insurer" name="selected_insurer" onchange="updateInsurerFields()">
    <option value="">登録済みデータから選択［保険者名称｜保険者番号｜保険者住所｜提出先名称］</option>
    @foreach($insurers as $insurer)
      <option value="{{ $insurer->id }}" 
        {{ old('selected_insurer') == $insurer->id ? 'selected' : '' }}
        data-number="{{ $insurer->insurer_number }}"
        data-name="{{ $insurer->insurer_name }}"
        data-postal="{{ $insurer->postal_code }}"
        data-address="{{ $insurer->address }}"
        data-recipient="{{ $insurer->recipient_name }}">
      {{ $insurer->insurer_name }}｜{{ $insurer->insurer_number }}｜{{ $insurer->postal_code }}｜{{ $insurer->address }}｜{{ $insurer->recipient_name }}
      </option>
    @endforeach
    </select>
  </div>

  <h6>▼ 保険者情報新規登録</h6>

  <div class="mb-3">
    <label for="new_insurer_number">保険者番号</label><br>
    <input type="text" id="new_insurer_number" name="new_insurer_number" value="{{ old('new_insurer_number', old('selected_insurer') ? '' : '') }}">
    <div id="insurer_number_warning" class="text-danger" style="display: none;">保険者番号は6桁または8桁の数字を入力してください</div>
    @error('new_insurer_number')
    <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="new_insurer_name">保険者名称</label><br>
    <input type="text" id="new_insurer_name" name="new_insurer_name" value="{{ old('new_insurer_name', old('selected_insurer') ? '' : '') }}">
  </div>

  <div class="mb-3">
    <label for="new_postal_code">郵便番号</label><br>
    <input type="text" id="new_postal_code" name="new_postal_code" placeholder="000-0000" maxlength="8" value="{{ old('new_postal_code', old('selected_insurer') ? '' : '') }}">
    <div id="new-address-message" class="loading" style="display: none; margin-top: 5px;"></div>
  </div>

  <div class="mb-3">
    <label for="new_address">住所</label><br>
    <input type="text" id="new_address" name="new_address" value="{{ old('new_address', old('selected_insurer') ? '' : '') }}">
  </div>

  <div class="mb-3">
    <label for="new_recipient_name">提出先名称</label><br>
    <input type="text" id="new_recipient_name" name="new_recipient_name" value="{{ old('new_recipient_name', old('selected_insurer') ? '' : '') }}">
  </div>
  @endif

  <button type="submit">{{ $submitLabel }}</button>
  <a href="{{ $cancelRoute }}">
  <button>キャンセル</button>
	</a>
</div>

@push('scripts')
  <script>
  function toggleMedicalAssistanceFields() {
    const medicalAssistanceTarget = document.getElementById('medical_assistance_target');
    const medicalAssistanceFields = document.getElementById('medical-assistance-fields');
    medicalAssistanceFields.style.display = medicalAssistanceTarget.checked ? 'block' : 'none';
  }
  </script>
  @if(!$isEdit)
  <script src="{{ asset('js/cii-registration.js') }}"></script>
  @endif
@endpush