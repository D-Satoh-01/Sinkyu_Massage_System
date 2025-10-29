<!-- resources/views/clinic-users-info/_form.blade.php -->

@php
  // 呼び出し元が渡す変数一覧:
  // - action (string): フォーム送信先のURL
  // - sessionKey (string): セッションキーのプレフィックス（例: 'registration_data' / 'edit_data'）
  // - clinicUser (object|null): 編集時はモデル、登録時は null
  // - isEdit (bool): 編集モードかどうか（true = 編集）
  // - includeId (bool): hidden の id を含めるかどうか
  $get = function($field, $default = '') use ($sessionKey, $clinicUser) {
  $fromModel = isset($clinicUser) && isset($clinicUser->$field) ? $clinicUser->$field : $default;
  // 日付はモデルが Carbon の場合、表示用に Y-m-d に整形すること。
  // 必要に応じて呼び出し元で調整すること。
  return old($field, session($sessionKey . '.' . $field, $fromModel));
  };
@endphp

<form action="{{ $action }}" method="POST">
  @csrf
  @if(!empty($includeId) && isset($clinicUser->id))
  <input type="hidden" name="id" value="{{ $clinicUser->id }}">
  @endif

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="clinic_user_name">利用者氏名</label>
  @error('clinic_user_name')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="text" id="clinic_user_name" name="clinic_user_name" value="{{ $get('clinic_user_name') }}" @if(!empty($isEdit)) required @endif>
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="furigana">フリガナ</label>
  @error('furigana')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="text" id="furigana" name="furigana" value="{{ $get('furigana') }}">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="birthday">生年月日</label><br>
  <input type="date" id="birthday" name="birthday" value="{{ old('birthday', session($sessionKey . '.birthday', isset($clinicUser) && !empty($clinicUser->birthday) ? ($clinicUser->birthday instanceof \Carbon\Carbon ? $clinicUser->birthday->format('Y-m-d') : $clinicUser->birthday) : '')) }}">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="age">年齢</label>
  @error('age')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="number" id="age" name="age" value="{{ $get('age') }}" min="0" max="150">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="gender_id">性別</label><br>
  @php $genderVal = old('gender_id', session($sessionKey . '.gender_id', isset($clinicUser) ? $clinicUser->gender_id ?? '' : '')); @endphp
  <select id="gender_id" name="gender_id">
    <option value="">----</option>
    <option value="1" {{ $genderVal == '1' ? 'selected' : '' }}>男性</option>
    <option value="2" {{ $genderVal == '2' ? 'selected' : '' }}>女性</option>
  </select>
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="postal_code">郵便番号</label>
  @error('postal_code')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="text" id="postal_code" name="postal_code" value="{{ $get('postal_code') }}" placeholder="000-0000" maxlength="8">
  <div id="address-message" class="loading" style="display: none; margin-top: 5px;"></div>
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="address_1">都道府県</label>
  @error('address_1')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="text" id="address_1" name="address_1" value="{{ $get('address_1') }}" readonly>
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="address_2">市区町村番地以下</label>
  @error('address_2')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="text" id="address_2" name="address_2" value="{{ $get('address_2') }}">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="address_3">アパート・マンション名等</label>
  @error('address_3')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="text" id="address_3" name="address_3" value="{{ $get('address_3') }}">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="phone">電話番号</label><br>
  <input type="text" id="phone" name="phone" value="{{ $get('phone') }}">
  </div>
  
  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="cell_phone">携帯番号</label><br>
  <input type="text" id="cell_phone" name="cell_phone" value="{{ $get('cell_phone') }}">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="fax">FAX番号</label><br>
  <input type="text" id="fax" name="fax" value="{{ $get('fax') }}">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="email">メールアドレス</label><br>
  <input type="email" id="email" name="email" value="{{ $get('email') }}">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="housecall_distance">往診距離（合計）</label><br>
  <input type="number" id="housecall_distance" name="housecall_distance" value="{{ $get('housecall_distance') }}" min="0">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="housecall_additional_distance">往診加算距離（2㎞を超える場合の加算距離です。上記往診距離が2㎞以上の場合自動で入力されます）</label><br>
  <input type="number" id="housecall_additional_distance" name="housecall_additional_distance" value="{{ $get('housecall_additional_distance') }}" min="0">
  </div>

  <div class="mb-3">
  <div class="checkbox-group">
    <label class="fw-semibold" style="font-size: 0.9rem;" for="is_redeemed">償還対象</label><br>
    @php $redeemed = old('is_redeemed', session($sessionKey . '.is_redeemed', isset($clinicUser) ? $clinicUser->is_redeemed ?? '' : '')); @endphp
    <input type="checkbox" id="is_redeemed" name="is_redeemed" value="1" {{ $redeemed ? 'checked' : '' }}>
  </div>
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="application_count">申請書提出開始回数［大阪市のみ］</label><br>
  <input type="number" id="application_count" name="application_count" value="{{ $get('application_count') }}" min="0">
  </div>

  <div class="mb-3">
  <label class="fw-semibold" style="font-size: 0.9rem;" for="note">メモ</label><br>
  <textarea id="note" name="note" rows="4">{{ $get('note') }}</textarea>
  </div>

  <button type="submit">登録確認へ</button>
</form>

@push('scripts')
  <script src="{{ asset('js/cui-registration.js') }}"></script>
@endpush
