<!-- resources/views/clinic-users-info/cui-insurances-info/cii-registration.blade.php -->


<x-app-layout>
  <h2>{{ $name }} 様の保険情報新規登録</h2>
  <br><br>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

<form action="{{ route('cui-insurances-info.confirm', $id) }}" method="POST">
    @csrf

    <div class="mb-3">
      <label>保険種別１</label><br>
      <input type="radio" id="insurance_type_1_company_national_union" name="insurance_type_1" value="社･国･組" {{ old('insurance_type_1') == '社･国･組' ? 'checked' : '' }}>
      <label for="insurance_type_1_company_national_union" class="me-3">社･国･組</label>
      <input type="radio" id="insurance_type_1_public_expense" name="insurance_type_1" value="公費" {{ old('insurance_type_1') == '公費' ? 'checked' : '' }}>
      <label for="insurance_type_1_public_expense" class="me-3">公費</label>
      <input type="radio" id="insurance_type_1_latter_period" name="insurance_type_1" value="後期" {{ old('insurance_type_1') == '後期' ? 'checked' : '' }}>
      <label for="insurance_type_1_latter_period" class="me-3">後期</label>
      <input type="radio" id="insurance_type_1_retirement" name="insurance_type_1" value="退職" {{ old('insurance_type_1') == '退職' ? 'checked' : '' }}>
      <label for="insurance_type_1_retirement" class="me-3">退職</label>
      @error('insurance_type_1')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label>保険種別２</label><br>
      <input type="radio" id="insurance_type_2_single" name="insurance_type_2" value="単独" {{ old('insurance_type_2') == '単独' ? 'checked' : '' }}>
      <label for="insurance_type_2_single" class="me-3">単独</label>
      <input type="radio" id="insurance_type_2_dual_combination" name="insurance_type_2" value="２併" {{ old('insurance_type_2') == '２併' ? 'checked' : '' }}>
      <label for="insurance_type_2_dual_combination" class="me-3">２併</label>
      <input type="radio" id="insurance_type_2_triple_combination" name="insurance_type_2" value="３併" {{ old('insurance_type_2') == '３併' ? 'checked' : '' }}>
      <label for="insurance_type_2_triple_combination" class="me-3">３併</label>
      @error('insurance_type_2')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label>保険種別３</label><br>
      <input type="radio" id="insurance_type_3_main_external" name="insurance_type_3" value="本外" {{ old('insurance_type_3') == '本外' ? 'checked' : '' }}>
      <label for="insurance_type_3_main_external" class="me-3">本外</label>
      <input type="radio" id="insurance_type_3_three_external" name="insurance_type_3" value="三外" {{ old('insurance_type_3') == '三外' ? 'checked' : '' }}>
      <label for="insurance_type_3_three_external" class="me-3">三外</label>
      <input type="radio" id="insurance_type_3_home_external" name="insurance_type_3" value="家外" {{ old('insurance_type_3') == '家外' ? 'checked' : '' }}>
      <label for="insurance_type_3_home_external" class="me-3">家外</label>
      <input type="radio" id="insurance_type_3_high_external_9" name="insurance_type_3" value="高外9" {{ old('insurance_type_3') == '高外9' ? 'checked' : '' }}>
      <label for="insurance_type_3_high_external_9" class="me-3">高外9</label>
      <input type="radio" id="insurance_type_3_high_external_8" name="insurance_type_3" value="高外8" {{ old('insurance_type_3') == '高外8' ? 'checked' : '' }}>
      <label for="insurance_type_3_high_external_8" class="me-3">高外8</label>
      @error('insurance_type_3')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label>本人・家族</label><br>
      <input type="radio" id="insured_person_type_self" name="insured_person_type" value="本人" {{ old('insured_person_type') == '本人' ? 'checked' : '' }}>
      <label for="insured_person_type_self" class="me-3">本人</label>
      <input type="radio" id="insured_person_type_six_years_old" name="insured_person_type" value="六歳" {{ old('insured_person_type') == '六歳' ? 'checked' : '' }}>
      <label for="insured_person_type_six_years_old" class="me-3">六歳</label>
      <input type="radio" id="insured_person_type_family" name="insured_person_type" value="家族" {{ old('insured_person_type') == '家族' ? 'checked' : '' }}>
      <label for="insured_person_type_family" class="me-3">家族</label>
      <input type="radio" id="insured_person_type_elderly_1" name="insured_person_type" value="高齢１" {{ old('insured_person_type') == '高齢１' ? 'checked' : '' }}>
      <label for="insured_person_type_elderly_1" class="me-3">高齢１</label>
      <input type="radio" id="insured_person_type_elderly" name="insured_person_type" value="高齢" {{ old('insured_person_type') == '高齢' ? 'checked' : '' }}>
      <label for="insured_person_type_elderly" class="me-3">高齢</label>
      <input type="radio" id="insured_person_type_elderly_7" name="insured_person_type" value="高齢７" {{ old('insured_person_type') == '高齢７' ? 'checked' : '' }}>
      <label for="insured_person_type_elderly_7" class="me-3">高齢７</label>
      @error('insured_person_type')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="insured_number">被保険者番号</label><br>
      <input type="text" id="insured_number" name="insured_number" value="{{ old('insured_number') }}">
      @error('insured_number')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="symbol">記号</label><br>
      <input type="text" id="symbol" name="symbol" value="{{ old('symbol') }}">
    </div>

    <div class="mb-3">
      <label for="number">番号</label><br>
      <input type="text" id="number" name="number" value="{{ old('number') }}">
    </div>

    <div class="mb-3">
      <label for="qualification_date">資格取得年月日</label><br>
      <input type="date" id="qualification_date" name="qualification_date" value="{{ old('qualification_date') }}">
    </div>

    <div class="mb-3">
      <label for="certification_date">認定年月日</label><br>
      <input type="date" id="certification_date" name="certification_date" value="{{ old('certification_date') }}">
    </div>

    <div class="mb-3">
      <label for="issue_date">発行（交付）年月日</label><br>
      <input type="date" id="issue_date" name="issue_date" value="{{ old('issue_date') }}">
    </div>

    <div class="mb-3">
      <label for="copayment_rate">一部負担金の割合</label><br>
      <select id="copayment_rate" name="copayment_rate">
        <option value="">----</option>
        <option value="1割" {{ old('copayment_rate') == '1割' ? 'selected' : '' }}>1割</option>
        <option value="2割" {{ old('copayment_rate') == '2割' ? 'selected' : '' }}>2割</option>
        <option value="3割" {{ old('copayment_rate') == '3割' ? 'selected' : '' }}>3割</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="expiration_date">有効期限</label><br>
      <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}">
    </div>

    <div class="mb-3">
      <div class="checkbox-group">
        <label for="reimbursement_target">償還対象</label><br>
        <input type="checkbox" id="reimbursement_target" name="reimbursement_target" value="1" {{ old('reimbursement_target') ? 'checked' : '' }}>
      </div>
    </div>

    <div class="mb-3">
      <label for="insured_person_name">被保険者氏名</label><br>
      <input type="text" id="insured_person_name" name="insured_person_name" value="{{ old('insured_person_name') }}">
    </div>

    <div class="mb-3">
      <label for="relationship">利用者との続柄</label><br>
      <select id="relationship" name="relationship">
        <option value="">----</option>
        <option value="本人" {{ old('relationship') == '本人' ? 'selected' : '' }}>本人</option>
        <option value="家族" {{ old('relationship') == '家族' ? 'selected' : '' }}>家族</option>
      </select>
    </div>

    <div class="mb-3">
      <div class="checkbox-group">
        <label for="medical_assistance_target">医療助成対象</label><br>
        <input type="checkbox" id="medical_assistance_target" name="medical_assistance_target" value="1" {{ old('medical_assistance_target') ? 'checked' : '' }} onchange="toggleMedicalAssistanceFields()">
      </div>
    </div>



    <div id="medical-assistance-fields">


      <div class="mb-3">
        <label for="public_burden_number">公費負担者番号</label><br>
        <input type="text" id="public_burden_number" name="public_burden_number" value="{{ old('public_burden_number') }}">
      </div>

      <div class="mb-3">
        <label for="public_recipient_number">公費受給者番号</label><br>
        <input type="text" id="public_recipient_number" name="public_recipient_number" value="{{ old('public_recipient_number') }}">
      </div>

      <div class="mb-3">
        <label for="municipal_code">区市町村番号</label><br>
        <input type="text" id="municipal_code" name="municipal_code" value="{{ old('municipal_code') }}">
      </div>

      <div class="mb-3">
        <label for="recipient_number">受給者番号</label><br>
        <input type="text" id="recipient_number" name="recipient_number" value="{{ old('recipient_number') }}">
      </div>
    </div>

    <div class="mb-3">
      <label for="selected_insurer">保険者情報</label><br>
      <select id="selected_insurer" name="selected_insurer" onchange="updateInsurerFields()">
        <option value="">登録済みデータから選択［保険者名称｜保険者番号｜保険者住所｜提出先名称］</option>
        @foreach($insurers as $insurer)
          <option value="{{ $insurer->id }}" {{ old('selected_insurer') == $insurer->id ? 'selected' : '' }} data-number="{{ $insurer->insurer_number }}" data-name="{{ $insurer->insurer_name }}" data-postal="{{ $insurer->postal_code }}" data-address="{{ $insurer->address }}" data-recipient="{{ $insurer->recipient_name }}">{{ $insurer->insurer_name }}｜{{ $insurer->insurer_number }}｜{{ $insurer->postal_code }}｜{{ $insurer->address }}｜{{ $insurer->recipient_name }}</option>
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

    <button type="submit">登録確認へ</button>
  </form>

  @push('scripts')
    <script src="{{ asset('js/cii-registration.js') }}"></script>
  @endpush
</x-app-layout>
