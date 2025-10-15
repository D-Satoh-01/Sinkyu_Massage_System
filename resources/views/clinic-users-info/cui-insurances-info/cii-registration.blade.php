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
      <label for="insurance_type_1">保険種別１</label><br>
      <select id="insurance_type_1" name="insurance_type_1">
        <option value="">----</option>
        <option value="社">社</option>
        <option value="国">国</option>
        <option value="組">組</option>
        <option value="公費">公費</option>
        <option value="後期">後期</option>
        <option value="退職">退職</option>
      </select>
      @error('insurance_type_1')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="insurance_type_2">保険種別２</label><br>
      <select id="insurance_type_2" name="insurance_type_2">
        <option value="">----</option>
        <option value="単独">単独</option>
        <option value="２併">２併</option>
        <option value="３併">３併</option>
      </select>
      @error('insurance_type_2')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="insurance_type_3">保険種別３</label><br>
      <select id="insurance_type_3" name="insurance_type_3">
        <option value="">----</option>
        <option value="本外">本外</option>
        <option value="三外">三外</option>
        <option value="家外">家外</option>
        <option value="高外9">高外9</option>
        <option value="高外8">高外8</option>
      </select>
      @error('insurance_type_3')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="insured_person_type">本人・家族</label><br>
      <select id="insured_person_type" name="insured_person_type">
        <option value="">----</option>
        <option value="本人">本人</option>
        <option value="六歳">六歳</option>
        <option value="家族">家族</option>
        <option value="高齢１">高齢１</option>
        <option value="高齢">高齢</option>
        <option value="高齢７">高齢７</option>
      </select>
      @error('insured_person_type')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="insured_number">被保険者番号</label><br>
      <input type="text" id="insured_number" name="insured_number">
      @error('insured_number')
        <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="symbol">記号</label><br>
      <input type="text" id="symbol" name="symbol">
    </div>

    <div class="mb-3">
      <label for="number">番号</label><br>
      <input type="text" id="number" name="number">
    </div>

    <div class="mb-3">
      <label for="qualification_date">資格取得年月日</label><br>
      <input type="date" id="qualification_date" name="qualification_date">
    </div>

    <div class="mb-3">
      <label for="certification_date">認定年月日</label><br>
      <input type="date" id="certification_date" name="certification_date">
    </div>

    <div class="mb-3">
      <label for="issue_date">発行（交付）年月日</label><br>
      <input type="date" id="issue_date" name="issue_date">
    </div>

    <div class="mb-3">
      <label for="copayment_rate">一部負担金の割合</label><br>
      <select id="copayment_rate" name="copayment_rate">
        <option value="">----</option>
        <option value="1割">1割</option>
        <option value="2割">2割</option>
        <option value="3割">3割</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="expiration_date">有効期限</label><br>
      <input type="date" id="expiration_date" name="expiration_date">
    </div>

    <div class="mb-3">
      <div class="checkbox-group">
        <label for="reimbursement_target">償還対象</label><br>
        <input type="checkbox" id="reimbursement_target" name="reimbursement_target" value="1">
      </div>
    </div>

    <div class="mb-3">
      <label for="insured_person_name">被保険者氏名</label><br>
      <input type="text" id="insured_person_name" name="insured_person_name">
    </div>

    <div class="mb-3">
      <label for="relationship">利用者との続柄</label><br>
      <select id="relationship" name="relationship">
        <option value="">----</option>
        <option value="本人">本人</option>
        <option value="家族">家族</option>
      </select>
    </div>

    <div class="mb-3">
      <div class="checkbox-group">
        <label for="medical_assistance_target">医療助成対象</label><br>
        <input type="checkbox" id="medical_assistance_target" name="medical_assistance_target" value="1" onchange="toggleMedicalAssistanceFields()">
      </div>
    </div>

    <div id="medical-assistance-fields" style="display: none;">
      <div class="mb-3">
        <label for="public_burden_number">公費負担者番号</label><br>
        <input type="text" id="public_burden_number" name="public_burden_number">
      </div>

      <div class="mb-3">
        <label for="public_recipient_number">公費受給者番号</label><br>
        <input type="text" id="public_recipient_number" name="public_recipient_number">
      </div>

      <div class="mb-3">
        <label for="municipal_code">区市町村番号</label><br>
        <input type="text" id="municipal_code" name="municipal_code">
      </div>

      <div class="mb-3">
        <label for="recipient_number">受給者番号</label><br>
        <input type="text" id="recipient_number" name="recipient_number">
      </div>
    </div>

    <div class="mb-3">
      <label for="selected_insurer">保険者情報</label><br>
      <select id="selected_insurer" name="selected_insurer" onchange="updateInsurerFields()">
        <option value="">登録済みデータから選択［保険者名称｜保険者番号｜保険者住所｜提出先名称］</option>
        @foreach($insurers as $insurer)
          <option value="{{ $insurer->id }}" data-number="{{ $insurer->insurer_number }}" data-name="{{ $insurer->insurer_name }}" data-postal="{{ $insurer->postal_code }}" data-address="{{ $insurer->address }}" data-recipient="{{ $insurer->recipient_name }}">{{ $insurer->insurer_name }}｜{{ $insurer->insurer_number }}｜{{ $insurer->postal_code }}｜{{ $insurer->address }}｜{{ $insurer->recipient_name }}</option>
        @endforeach
      </select>
    </div>

    <h6>▼ 保険者情報新規登録</h6>

    <div class="mb-3">
      <label for="new_insurer_number">保険者番号</label><br>
      <input type="text" id="new_insurer_number" name="new_insurer_number">
    </div>

    <div class="mb-3">
      <label for="new_insurer_name">保険者名称</label><br>
      <input type="text" id="new_insurer_name" name="new_insurer_name">
    </div>

    <div class="mb-3">
      <label for="new_postal_code">郵便番号</label><br>
      <input type="text" id="new_postal_code" name="new_postal_code" placeholder="000-0000" maxlength="8">
      <div id="new-address-message" class="loading" style="display: none; margin-top: 5px;"></div>
    </div>

    <div class="mb-3">
      <label for="new_address">住所</label><br>
      <input type="text" id="new_address" name="new_address">
    </div>

    <div class="mb-3">
      <label for="new_recipient_name">提出先名称</label><br>
      <input type="text" id="new_recipient_name" name="new_recipient_name">
    </div>

    <button type="submit">登録確認へ</button>
  </form>

  @push('scripts')
    <script src="{{ asset('js/cii-registration.js') }}"></script>
  @endpush
</x-app-layout>
