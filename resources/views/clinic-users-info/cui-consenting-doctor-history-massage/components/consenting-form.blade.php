{{-- resources/views/clinic-users-info/cui-consenting-doctor-history-massage/components/consenting-form.blade.php --}}

<div class="consenting-form">
  @csrf

  <div class="mb-3">
    <label for="consenting_doctor_name">同意医師名</label><br>
    <input type="text" id="consenting_doctor_name" name="consenting_doctor_name" value="{{ old('consenting_doctor_name', $history?->consenting_doctor_name ?? '') }}">
    @error('consenting_doctor_name')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="consenting_date">同意日</label><br>
    <input type="date" id="consenting_date" name="consenting_date" value="{{ old('consenting_date', $history?->consenting_date?->format('Y-m-d') ?? '') }}">
    @error('consenting_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="consenting_start_date">同意開始日</label><br>
    <input type="date" id="consenting_start_date" name="consenting_start_date" value="{{ old('consenting_start_date', $history?->consenting_start_date?->format('Y-m-d') ?? '') }}">
    @error('consenting_start_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="consenting_end_date">同意終了日</label><br>
    <input type="date" id="consenting_end_date" name="consenting_end_date" value="{{ old('consenting_end_date', $history?->consenting_end_date?->format('Y-m-d') ?? '') }}">
    @error('consenting_end_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="benefit_period_start_date">給付期間開始日</label><br>
    <input type="date" id="benefit_period_start_date" name="benefit_period_start_date" value="{{ old('benefit_period_start_date', $history?->benefit_period_start_date?->format('Y-m-d') ?? '') }}">
    @error('benefit_period_start_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="benefit_period_end_date">給付期間終了日</label><br>
    <input type="date" id="benefit_period_end_date" name="benefit_period_end_date" value="{{ old('benefit_period_end_date', $history?->benefit_period_end_date?->format('Y-m-d') ?? '') }}">
    @error('benefit_period_end_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="first_care_date">初療日</label><br>
    <input type="date" id="first_care_date" name="first_care_date" value="{{ old('first_care_date', $history?->first_care_date?->format('Y-m-d') ?? '') }}">
    @error('first_care_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="reconsenting_expiry">再同意期限</label><br>
    <input type="date" id="reconsenting_expiry" name="reconsenting_expiry" value="{{ old('reconsenting_expiry', $history?->reconsenting_expiry?->format('Y-m-d') ?? '') }}">
    @error('reconsenting_expiry')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="onset_and_injury_date">発症・負傷日</label><br>
    <input type="date" id="onset_and_injury_date" name="onset_and_injury_date" value="{{ old('onset_and_injury_date', $history?->onset_and_injury_date?->format('Y-m-d') ?? '') }}">
    @error('onset_and_injury_date')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="notes">備考</label><br>
    <textarea id="notes" name="notes" rows="3">{{ old('notes', $history?->notes ?? '') }}</textarea>
    @error('notes')
      <div class="text-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <button type="submit">{{ $submitLabel }}</button>
    <a href="{{ $cancelRoute }}">
    <button>キャンセル</button>
    </a>
  </div>
</div>
