<x-app-layout>
  <h2>利用者新規登録</h2><br><br>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
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

  <form action="{{ route('cui-registration.store') }}" method="POST">
    @csrf
    
    <!-- 基本情報 -->
    <div>
      <label for="clinic_user_name">利用者氏名:</label>
      <input type="text" id="clinic_user_name" name="clinic_user_name" value="{{ old('clinic_user_name') }}" required>
    </div>

    <div>
      <label for="furigana">フリガナ:</label>
      <input type="text" id="furigana" name="furigana" value="{{ old('furigana') }}">
    </div>

    <div>
      <label for="birthday">生年月日:</label>
      <input type="date" id="birthday" name="birthday" value="{{ old('birthday') }}">
    </div>

    <div>
      <label for="age">年齢:</label>
      <input type="number" id="age" name="age" value="{{ old('age') }}" min="0" max="150">
    </div>

    <div>
      <label for="gender_id">性別:</label>
      <select id="gender_id" name="gender_id">
        <option value="">----</option>
        <option value="1" {{ old('gender_id') == '1' ? 'selected' : '' }}>男性</option>
        <option value="2" {{ old('gender_id') == '2' ? 'selected' : '' }}>女性</option>
      </select>
    </div>

    <!-- 住所情報 -->
    <div>
      <label for="postal_code">郵便番号:</label>
      <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="000-0000" maxlength="8">
      <div id="address-message" class="loading" style="display: none; margin-top: 5px;"></div>
    </div>

    <div>
      <label for="address_1">都道府県:</label>
      <input type="text" id="address_1" name="address_1" value="{{ old('address_1') }}" readonly>
    </div>

    <div>
      <label for="address_2">市区町村番地以下:</label>
      <input type="text" id="address_2" name="address_2" value="{{ old('address_2') }}">
    </div>

    <div>
      <label for="address_3">アパート・マンション名等:</label>
      <input type="text" id="address_3" name="address_3" value="{{ old('address_3') }}">
    </div>

    <!-- 連絡先情報 -->
    <div>
      <label for="phone">電話番号:</label>
      <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
    </div>
    
    <div>
      <label for="cell_phone">携帯番号:</label>
      <input type="text" id="cell_phone" name="cell_phone" value="{{ old('cell_phone') }}">
    </div>

    <div>
      <label for="fax">FAX番号:</label>
      <input type="text" id="fax" name="fax" value="{{ old('fax') }}">
    </div>

    <div>
      <label for="email">メールアドレス:</label>
      <input type="email" id="email" name="email" value="{{ old('email') }}">
    </div>

    <!-- 往診情報 -->
    <div>
      <label for="housecall_distance">往診距離（合計）:</label>
      <input type="number" id="housecall_distance" name="housecall_distance" value="{{ old('housecall_distance') }}" min="0">
    </div>

    <div>
      <label for="housecall_additional_distance">往診加算距離:</label>
      <input type="number" id="housecall_additional_distance" name="housecall_additional_distance" value="{{ old('housecall_additional_distance') }}" min="0">
    </div>

    <!-- その他情報 -->
    <div>
      <div class="checkbox-group">
        <input type="checkbox" id="is_redeemed" name="is_redeemed" value="1" {{ old('is_redeemed') ? 'checked' : '' }}>
        <label for="is_redeemed">償還対象</label>
      </div>
    </div>

    <div>
      <label for="application_count">申請書提出開始回数[大阪市のみ]:</label>
      <input type="number" id="application_count" name="application_count" value="{{ old('application_count') }}" min="0">
    </div>

    <div>
      <label for="note">メモ:</label>
      <textarea id="note" name="note" rows="4">{{ old('note') }}</textarea>
    </div>

    <button type="submit">登録確認へ</button>
  </form>

  @push('scripts')
    <script src="{{ asset('js/cui-registration.js') }}"></script>
  @endpush
</x-app-layout>
