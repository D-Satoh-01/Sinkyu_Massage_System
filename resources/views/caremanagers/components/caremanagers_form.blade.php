{{-- resources/views/caremanagers/components/caremanagers_form.blade.php --}}

<div class="caremanager-form">
  @csrf

  <div class="mb-3">
    <label class="fw-semibold" for="care_manager_name">ケアマネ氏名 <span class="text-danger">*</span></label>
    @error('care_manager_name')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="care_manager_name" name="care_manager_name" value="{{ old('care_manager_name', $careManager->care_manager_name ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="service_provider_name">サービス事業者名</label>
    @error('service_provider_name')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <select id="service_provider_name" name="service_provider_name">
      <option value="">----</option>
      <!-- サービス事業者のオプションはここに追加 -->
    </select>
    <br>
    <span style="font-size: 0.9em;">上記選択にない場合、下記に入力する事でマスターとして登録します。</span><br>
    <span style="font-size: 0.9em;">もしくは<a href="#">こちらから</a>登録してください。</span>
    <br>
    <input type="text" id="service_provider_name_custom" name="service_provider_name_custom" placeholder="入力されたデータをマスターとして新規登録します。" value="{{ old('service_provider_name_custom') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="furigana">フリガナ</label>
    @error('furigana')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="furigana" name="furigana" value="{{ old('furigana', $careManager->furigana ?? '') }}">
  </div>

  <br>

  <div class="mb-3">
    <label class="fw-semibold" for="postal_code">住所</label>
    @error('postal_code')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <span style="font-size: 0.9em;">郵便番号を入力すると住所が自動で入力されます <a href="https://www.post.japanpost.jp/zipcode/" target="_blank">[日本郵便HPへ]</a></span>
    <br>
    <label for="postal_code">(郵便番号)</label><br>
    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $careManager->postal_code ?? '') }}" placeholder="000-0000" maxlength="8">
    <div id="caremanager-address-message" class="loading" style="display: none; margin-top: 5px;"></div>
  </div>

  <div class="mb-3">
    <label for="address_1">(都道府県)</label>
    @error('address_1')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="address_1" name="address_1" value="{{ old('address_1', $careManager->address_1 ?? '') }}" readonly>
  </div>

  <div class="mb-3">
    <label for="address_2">(市区町村番地以下)</label>
    @error('address_2')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="address_2" name="address_2" value="{{ old('address_2', $careManager->address_2 ?? '') }}">
  </div>

  <div class="mb-3">
    <label for="address_3">(アパート・マンション名等)</label>
    @error('address_3')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="address_3" name="address_3" value="{{ old('address_3', $careManager->address_3 ?? '') }}">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="phone">電話番号</label>
    @error('phone')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="phone" name="phone" value="{{ old('phone', $careManager->phone ?? '') }}" placeholder="03-1234-5678">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="cell_phone">携帯番号</label>
    @error('cell_phone')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="cell_phone" name="cell_phone" value="{{ old('cell_phone', $careManager->cell_phone ?? '') }}" placeholder="03-1234-5678">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="fax">FAX番号</label>
    @error('fax')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="text" id="fax" name="fax" value="{{ old('fax', $careManager->fax ?? '') }}" placeholder="03-1234-5679">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="email">メールアドレス</label>
    @error('email')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <input type="email" id="email" name="email" value="{{ old('email', $careManager->email ?? '') }}" placeholder="yamada@google.co.jp">
  </div>

  <div class="mb-3">
    <label class="fw-semibold" for="note">メモ</label>
    @error('note')
      <span class="text-danger ms-2">{{ $message }}</span>
    @enderror
    <br>
    <textarea id="note" name="note" rows="4">{{ old('note', $careManager->note ?? '') }}</textarea>
  </div>

  <button type="submit">{{ $submitLabel }}</button>
  <a href="{{ $cancelRoute }}">
    <button type="button">キャンセル</button>
  </a>
</div>

@push('scripts')
  <script src="{{ asset('js/utility.js') }}"></script>
  <script src="{{ asset('js/caremanagers.js') }}"></script>
@endpush
