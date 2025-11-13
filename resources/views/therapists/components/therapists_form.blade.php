{{-- resources/views/therapists/components/therapists_form.blade.php --}}

<div class="therapist-form">
  @csrf

  <div class="mb-3">
    <label class="fw-semibold" for="therapist_name">施術者名 <span class="text-danger"></span></label>
    @error('therapist_name')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="therapist_name" name="therapist_name" value="{{ old('therapist_name', $therapist->therapist_name ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="furigana">フリガナ</label>
    @error('furigana')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="furigana" name="furigana" value="{{ old('furigana', $therapist->furigana ?? '') }}">
  </div>

  <br>

  <div class="mb-3">
    <label class="fw-semibold" for="postal_code">郵便番号</label>
    @error('postal_code')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $therapist->postal_code ?? '') }}" placeholder="000-0000" maxlength="8">
    <div id="therapist-address-message" class="loading" style="display: none; margin-top: 5px;"></div>
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="address_1">都道府県</label>
    @error('address_1')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="address_1" name="address_1" value="{{ old('address_1', $therapist->address_1 ?? '') }}" readonly>
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="address_2">市区町村番地以下</label>
    @error('address_2')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="address_2" name="address_2" value="{{ old('address_2', $therapist->address_2 ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="address_3">アパート・マンション名等</label>
    @error('address_3')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="address_3" name="address_3" value="{{ old('address_3', $therapist->address_3 ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="phone">電話番号</label>
    @error('phone')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="phone" name="phone" value="{{ old('phone', $therapist->phone ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="cell_phone">携帯番号</label>
    @error('cell_phone')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="cell_phone" name="cell_phone" value="{{ old('cell_phone', $therapist->cell_phone ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="fax">FAX番号</label>
    @error('fax')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="fax" name="fax" value="{{ old('fax', $therapist->fax ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="email">メールアドレス</label>
    @error('email')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="email" id="email" name="email" value="{{ old('email', $therapist->email ?? '') }}">
  </div>

  <br>

  <h4>資格（はり）</h4>

  <div class="mb-3">
    <label class="fw-semibold" for="license_hari_id">免許証記号番号</label>
    @error('license_hari_id')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="license_hari_id" name="license_hari_id" value="{{ old('license_hari_id', $therapist->license_hari_id ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="license_hari_number">免許証番号</label>
    @error('license_hari_number')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="license_hari_number" name="license_hari_number" value="{{ old('license_hari_number', $therapist->license_hari_number ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="license_hari_issued_date">免許証交付年月日</label>
    @error('license_hari_issued_date')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="date" id="license_hari_issued_date" name="license_hari_issued_date" value="{{ old('license_hari_issued_date', $therapist->license_hari_issued_date ?? '') }}">
  </div>

  <br>

  <h4>資格（きゅう）</h4>

  <div class="mb-3">
    <label class="fw-semibold" for="license_kyu_id">免許証記号番号</label>
    @error('license_kyu_id')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="license_kyu_id" name="license_kyu_id" value="{{ old('license_kyu_id', $therapist->license_kyu_id ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="license_kyu_number">免許証番号</label>
    @error('license_kyu_number')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="license_kyu_number" name="license_kyu_number" value="{{ old('license_kyu_number', $therapist->license_kyu_number ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="license_kyu_issued_date">免許証交付年月日</label>
    @error('license_kyu_issued_date')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="date" id="license_kyu_issued_date" name="license_kyu_issued_date" value="{{ old('license_kyu_issued_date', $therapist->license_kyu_issued_date ?? '') }}">
  </div>

  <br>

  <h4>資格（あん摩・マッサージ）</h4>

  <div class="mb-3">
    <label class="fw-semibold" for="license_massage_id">免許証記号番号</label>
    @error('license_massage_id')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="license_massage_id" name="license_massage_id" value="{{ old('license_massage_id', $therapist->license_massage_id ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="license_massage_number">免許証番号</label>
    @error('license_massage_number')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="license_massage_number" name="license_massage_number" value="{{ old('license_massage_number', $therapist->license_massage_number ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="license_massage_issued_date">免許証交付年月日</label>
    @error('license_massage_issued_date')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="date" id="license_massage_issued_date" name="license_massage_issued_date" value="{{ old('license_massage_issued_date', $therapist->license_massage_issued_date ?? '') }}">
  </div>

  <br>

  <div class="mb-3">
    <label class="fw-semibold" for="member_number">大阪市発行の会員番号</label>
    @error('member_number')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="number" id="member_number" name="member_number" value="{{ old('member_number', $therapist->member_number ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="note">メモ</label>
    @error('note')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <textarea id="note" name="note" rows="4">{{ old('note', $therapist->note ?? '') }}</textarea>
  </div>

  <button type="submit">{{ $submitLabel }}</button>
  <a href="{{ $cancelRoute }}">
    <button type="button">キャンセル</button>
  </a>
</div>

@push('scripts')
  <script src="{{ asset('js/utility.js') }}"></script>
  <script src="{{ asset('js/therapists.js') }}"></script>
@endpush
