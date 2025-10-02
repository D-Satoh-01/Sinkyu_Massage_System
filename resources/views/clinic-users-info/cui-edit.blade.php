<!-- resources/views/clinic-users-info/cui-edit.blade.php -->

<x-app-layout>
  <h2>利用者情報編集</h2><br><br>

  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif
  
  @if($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('cui-edit.confirm') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $clinicUser->id }}">
    
    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="clinic_user_name">利用者氏名</label><br>
      <input type="text" id="clinic_user_name" name="clinic_user_name" value="{{ old('clinic_user_name', session('edit_data.clinic_user_name', $clinicUser->clinic_user_name)) }}" required>
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="furigana">フリガナ</label><br>
      <input type="text" id="furigana" name="furigana" value="{{ old('furigana', session('edit_data.furigana', $clinicUser->furigana)) }}">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="birthday">生年月日</label><br>
      <input type="date" id="birthday" name="birthday" value="{{ old('birthday', session('edit_data.birthday', $clinicUser->birthday ? $clinicUser->birthday->format('Y-m-d') : '')) }}">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="age">年齢</label><br>
      <input type="number" id="age" name="age" value="{{ old('age', session('edit_data.age', $clinicUser->age)) }}" min="0" max="150">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="gender_id">性別</label><br>
      <select id="gender_id" name="gender_id">
        <option value="">----</option>
        <option value="1" {{ old('gender_id', session('edit_data.gender_id', $clinicUser->gender_id)) == '1' ? 'selected' : '' }}>男性</option>
        <option value="2" {{ old('gender_id', session('edit_data.gender_id', $clinicUser->gender_id)) == '2' ? 'selected' : '' }}>女性</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="postal_code">郵便番号</label><br>
      <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', session('edit_data.postal_code', $clinicUser->postal_code)) }}" placeholder="000-0000" maxlength="8">
      <div id="address-message" class="loading" style="display: none; margin-top: 5px;"></div>
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="address_1">（都道府県）</label><br>
      <input type="text" id="address_1" name="address_1" value="{{ old('address_1', session('edit_data.address_1', $clinicUser->address_1)) }}" readonly>
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="address_2">（市区町村番地以下）</label><br>
      <input type="text" id="address_2" name="address_2" value="{{ old('address_2', session('edit_data.address_2', $clinicUser->address_2)) }}">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="address_3">（アパート・マンション名等）</label><br>
      <input type="text" id="address_3" name="address_3" value="{{ old('address_3', session('edit_data.address_3', $clinicUser->address_3)) }}">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="phone">電話番号</label><br>
      <input type="text" id="phone" name="phone" value="{{ old('phone', session('edit_data.phone', $clinicUser->phone)) }}">
    </div>
    
    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="cell_phone">携帯番号</label><br>
      <input type="text" id="cell_phone" name="cell_phone" value="{{ old('cell_phone', session('edit_data.cell_phone', $clinicUser->cell_phone)) }}">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="fax">FAX番号</label><br>
      <input type="text" id="fax" name="fax" value="{{ old('fax', session('edit_data.fax', $clinicUser->fax)) }}">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="email">メールアドレス</label><br>
      <input type="email" id="email" name="email" value="{{ old('email', session('edit_data.email', $clinicUser->email)) }}">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="housecall_distance">往診距離（合計）</label><br>
      <input type="number" id="housecall_distance" name="housecall_distance" value="{{ old('housecall_distance', session('edit_data.housecall_distance', $clinicUser->housecall_distance)) }}" min="0">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="housecall_additional_distance">往診加算距離（2㎞を超える場合の加算距離です。上記往診距離が2㎞以上の場合自動で入力されます）</label><br>
      <input type="number" id="housecall_additional_distance" name="housecall_additional_distance" value="{{ old('housecall_additional_distance', session('edit_data.housecall_additional_distance', $clinicUser->housecall_additional_distance)) }}" min="0">
    </div>

    <div class="mb-3">
      <div class="checkbox-group">
        <label class="fw-semibold" style="font-size: 0.9rem;" for="is_redeemed">償還対象</label><br>
        <input type="checkbox" id="is_redeemed" name="is_redeemed" value="1" {{ old('is_redeemed', session('edit_data.is_redeemed', $clinicUser->is_redeemed)) ? 'checked' : '' }}>
      </div>
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="application_count">申請書提出開始回数［大阪市のみ］</label><br>
      <input type="number" id="application_count" name="application_count" value="{{ old('application_count', session('edit_data.application_count', $clinicUser->application_count)) }}" min="0">
    </div>

    <div class="mb-3">
      <label class="fw-semibold" style="font-size: 0.9rem;" for="note">メモ</label><br>
      <textarea id="note" name="note" rows="4">{{ old('note', session('edit_data.note', $clinicUser->note)) }}</textarea>
    </div>

    <button type="submit">登録確認へ</button>
  </form>

  @push('scripts')
    <script src="{{ asset('js/cui-registration.js') }}"></script>
  @endpush
</x-app-layout>