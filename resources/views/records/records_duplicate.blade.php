<!-- resources/views/records/records_duplicate.blade.php -->


<x-app-layout>
  <x-page-header
    :title="$page_header_title"
    :breadcrumbs="App\Support\Breadcrumbs::generate('records.duplicate')"
  />

  @if($errors->any())
  <div class="alert alert-danger">
    <ul>
    @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
    </ul>
  </div>
  @endif

  <form id="recordDuplicateForm" method="POST" action="{{ route('records.duplicate.store') }}">
    @csrf
    <input type="hidden" name="clinic_user_id" value="{{ $record->clinic_user_id }}">

    <div class="d-flex gap-3 align-items-start">
      <!-- 繧ｫ繝ｬ繝ｳ繝繝ｼ -->
      <div class="text-center position-relative" style="width: 15rem;">
        <div id="calendar-title-display" class="fs-4 fw-bold py-1 d-inline-block" style="cursor: default;"></div>
        <select id="calendar-title" class="position-absolute top-0 start-50 translate-middle-x opacity-0" style="cursor: pointer; font-size: 1.5rem; padding: 0.2em 0em; border: none; background: transparent;"></select>
        <div class="calendar" id="calendar">
          <!-- 譖懈律繝倥ャ繝繝ｼ -->
          <div class="calendar-day-header sunday">譌･</div>
          <div class="calendar-day-header">譛・/div>
          <div class="calendar-day-header">轣ｫ</div>
          <div class="calendar-day-header">豌ｴ</div>
          <div class="calendar-day-header">譛ｨ</div>
          <div class="calendar-day-header">驥・/div>
          <div class="calendar-day-header saturday">蝨・/div>
        </div>
        <button type="button" id="clear-selection-btn" class="mt-2">驕ｸ謚櫁ｧ｣髯､</button>
      </div>

      <div class="vr border border-black border-1 mx-3"></div>

      <!-- 螳溽ｸｾ繝輔ぅ繝ｼ繝ｫ繝・-->
      <div class="flex-grow-1" id="record-fields">
        <!-- 譁ｽ陦鍋ｨｮ鬘・-->
        <div class="d-flex">
          <label class="fw-semibold">譁ｽ陦鍋ｨｮ鬘・/label>
          @error('therapy_type')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-2 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <div>
            <label><input type="radio" name="therapy_type" value="1" id="therapy_type_acupuncture" {{ old('therapy_type', $record->therapy_type) == '1' ? 'checked' : '' }}>縺ｯ繧奇ｽ･縺阪ｅ縺・/label>
            <label class="ms-3"><input type="radio" name="therapy_type" value="2" id="therapy_type_massage" {{ old('therapy_type', $record->therapy_type) == '2' ? 'checked' : '' }}>縺ゅｓ縺ｾ・･繝槭ャ繧ｵ繝ｼ繧ｸ</label>
          </div>
        </div>
        <div class="mb-3">
          <!-- 霄ｫ菴馴Κ菴阪メ繧ｧ繝・け繝懊ャ繧ｯ繧ｹ(縺ゅｓ縺ｾ・･繝槭ャ繧ｵ繝ｼ繧ｸ驕ｸ謚樊凾縺ｮ縺ｿ陦ｨ遉ｺ) -->
          <div id="bodyparts-section" class="{{ $record->therapy_type == 2 ? '' : 'd-none' }}">
            <label class="fw-semibold">縲縲驛ｨ菴・/label>
            <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
            <label><input type="checkbox" name="bodyparts[]" value="1" {{ in_array('1', old('bodyparts', $selectedBodyparts)) ? 'checked' : '' }}> 霄ｯ蟷ｹ</label>
            <label><input type="checkbox" name="bodyparts[]" value="2" {{ in_array('2', old('bodyparts', $selectedBodyparts)) ? 'checked' : '' }}> 蜿ｳ荳願い</label>
            <label><input type="checkbox" name="bodyparts[]" value="3" {{ in_array('3', old('bodyparts', $selectedBodyparts)) ? 'checked' : '' }}> 蟾ｦ荳願い</label>
            <label><input type="checkbox" name="bodyparts[]" value="4" {{ in_array('4', old('bodyparts', $selectedBodyparts)) ? 'checked' : '' }}> 蜿ｳ荳玖い</label>
            <label><input type="checkbox" name="bodyparts[]" value="5" {{ in_array('5', old('bodyparts', $selectedBodyparts)) ? 'checked' : '' }}> 蟾ｦ荳玖い</label>
          </div>
        </div>

        <!-- 譁ｽ陦灘玄蛻・-->
        <div class="mb-3">
          <label class="fw-semibold">譁ｽ陦灘玄蛻・/label>
          @error('therapy_category')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <label><input type="radio" name="therapy_category" value="1" id="therapy_category_visit" {{ old('therapy_category', $record->therapy_category) == '1' ? 'checked' : '' }}> 騾夐劼</label>
          <label class="ms-3"><input type="radio" name="therapy_category" value="2" id="therapy_category_housecall" {{ old('therapy_category', $record->therapy_category) == '2' ? 'checked' : '' }}> 蠕逋・/label>
        </div>

        <!-- 蠕逋りｷ晞屬(蠕逋る∈謚樊凾縺ｮ縺ｿ陦ｨ遉ｺ) -->
        <div id="housecall-distance-section" class="{{ $record->therapy_category == 2 ? '' : 'd-none' }} mb-3">
          <label class="d-block mb-1 fw-bold">蠕逋りｷ晞屬</label>
          <p class="my-1 small text-secondary">蠕逋よ侭縺檎匱逕溘☆繧句ｴ蜷医・蠕逋りｷ晞屬繧貞・蜉・蠕逋よ侭辟｡縺励↑繧・繧貞・蜉・</p>
          <div id="housecall-distance-inputs"></div>
          <div class="mt-2">
            荳願ｨ俶律莉倥ｒ蜈ｨ縺ｦ <input type="number" id="bulk-distance" step="0.5" min="0" style="width: 80px;"> km 縺ｫ
            <button type="button" id="apply-bulk-distance">螟画峩</button>
          </div>
        </div>

        <!-- 髢句ｧ区凾蛻ｻ & 邨ゆｺ・凾蛻ｻ -->
        <div class="mb-3">
          <label class="fw-semibold">髢句ｧ区凾蛻ｻ</label>
          @error('start_time')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <div class="time-picker-wrapper" id="start-time-picker"></div>
          <input type="hidden" id="start_time" name="start_time" value="{{ old('start_time', $record->start_time ? date('G:i', strtotime($record->start_time)) : '') }}">
        </div>

        <div class="mb-3">
          <label class="fw-semibold">邨ゆｺ・凾蛻ｻ</label>
          @error('end_time')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <div class="time-picker-wrapper" id="end-time-picker"></div>
          <input type="hidden" id="end_time" name="end_time" value="{{ old('end_time', $record->end_time ? date('G:i', strtotime($record->end_time)) : '') }}">
        </div>

        <!-- 譁ｽ陦灘・螳ｹ -->
        <div class="mb-3">
          <label class="fw-semibold" for="therapy_content_id">譁ｽ陦灘・螳ｹ</label>
          @error('therapy_content_id')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <select id="therapy_content_id" name="therapy_content_id">
            <option value="">驕ｸ謚槭＠縺ｦ縺上□縺輔＞</option>
            @foreach($therapyContents as $content)
              <option value="{{ $content->id }}" {{ old('therapy_content_id', $record->therapy_conetnt_id) == $content->id ? 'selected' : '' }}>{{ $content->therapy_content }}</option>
            @endforeach
          </select>

          <!-- 隍・｣ｽ繝√ぉ繝・け繝懊ャ繧ｯ繧ｹ(縺ゅｓ縺ｾ・･繝槭ャ繧ｵ繝ｼ繧ｸ驕ｸ謚樊凾縺ｮ縺ｿ陦ｨ遉ｺ) -->
          <div id="therapy-content-duplication" class="{{ $record->therapy_type == 2 ? '' : 'd-none' }} mt-2 ms-3">
            <label><input type="checkbox" name="duplicate_massage" value="1" {{ old('duplicate_massage') ? 'checked' : '' }}> 繝槭ャ繧ｵ繝ｼ繧ｸ繧貞酔荳蜀・ｮｹ縺ｧ隍・｣ｽ縺吶ｋ</label><br>
            <label><input type="checkbox" name="duplicate_warm_compress" value="1" {{ old('duplicate_warm_compress') ? 'checked' : '' }}> 貂ｩ鄂ｨ豕輔ｒ蜷御ｸ蜀・ｮｹ縺ｧ隍・｣ｽ縺吶ｋ</label><br>
            <label><input type="checkbox" name="duplicate_warm_electric" value="1" {{ old('duplicate_warm_electric') ? 'checked' : '' }}> 貂ｩ鄂ｨ豕輔・髮ｻ豌怜・邱壼勣蜈ｷ繧貞酔荳蜀・ｮｹ縺ｧ隍・｣ｽ縺吶ｋ</label><br>
            <label><input type="checkbox" name="duplicate_manual_correction" value="1" {{ old('duplicate_manual_correction') ? 'checked' : '' }}> 螟牙ｽ｢蠕呈焔遏ｯ豁｣陦薙ｒ蜷御ｸ蜀・ｮｹ縺ｧ隍・｣ｽ縺吶ｋ</label>
          </div>
        </div>

        <!-- 譁ｽ陦楢・-->
        <div class="mb-3">
          <label class="fw-semibold" for="therapist_id">譁ｽ陦楢・/label>
          @error('therapist_id')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <select id="therapist_id" name="therapist_id">
            <option value="">驕ｸ謚槭＠縺ｦ縺上□縺輔＞</option>
            @foreach($therapists as $therapist)
              <option value="{{ $therapist->id }}" {{ old('therapist_id', $record->therapist_id) == $therapist->id ? 'selected' : '' }}>{{ $therapist->last_name }} {{ $therapist->first_name }} @if($therapist->last_name_kana)({{ $therapist->last_name_kana }} {{ $therapist->first_name_kana }})@endif</option>
            @endforeach
          </select>
        </div>

        <!-- 菫晞匱蛹ｺ蛻・-->
        <div class="mb-3">
          <label class="fw-semibold">菫晞匱蛹ｺ蛻・/label>
          @error('insurance_category')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          @if($insurances && $insurances->count() > 0)
            <select name="insurance_category">
              <option value="">驕ｸ謚槭＠縺ｦ縺上□縺輔＞</option>
              @foreach($insurances as $insurance)
                @php
                  $insurerNumberLength = strlen($insurance->insurer_number ?? '');
                  $insuranceType = '';
                  if($insurerNumberLength == 6) {
                    $insuranceType = '蝗ｽ豌大▼蠎ｷ菫晞匱';
                  } elseif($insurerNumberLength == 8) {
                    $insuranceType = '邨・粋菫晞匱';
                  } else {
                    $insuranceType = '菫晞匱';
                  }
                  $expiryDate = $insurance->expiry_date ? date('Y/n/j', strtotime($insurance->expiry_date)) : '譛ｪ險ｭ螳・;
                @endphp
                <option value="{{ $insurance->id }}" {{ old('insurance_category', $record->insurance_category) == $insurance->id ? 'selected' : '' }}>{{ $insuranceType }}(譛滄剞:{{ $expiryDate }})</option>
              @endforeach
            </select>
          @else
            <p class="text-secondary">菫晞匱諠・ｱ縺檎匳骭ｲ縺輔ｌ縺ｦ縺・∪縺帙ｓ</p>
          @endif
        </div>

        <!-- 蜷梧э譛牙柑譛滄剞 -->
        <div class="mb-3 d-flex">
          <label class="mb-1 fw-bold">蜷梧э譛牙柑譛滄剞</label>
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <div id="consent-expiry-display">
            <span id="consent-expiry-acupuncture" class="{{ $record->therapy_type == 1 ? '' : 'd-none' }}">
              @if($consentsAcupuncture && $consentsAcupuncture->consenting_end_date)
                {{ date('Y/n/j', strtotime($consentsAcupuncture->consenting_end_date)) }}
              @else
                譛ｪ逋ｻ骭ｲ
              @endif
            </span>
            <span id="consent-expiry-massage" class="{{ $record->therapy_type == 2 ? '' : 'd-none' }}">
              @if($consentsMassage && $consentsMassage->consenting_end_date)
                {{ date('Y/n/j', strtotime($consentsMassage->consenting_end_date)) }}
              @else
                譛ｪ逋ｻ骭ｲ
              @endif
            </span>
          </div>
          <input type="hidden" name="consent_expiry" id="consent_expiry" value="{{ old('consent_expiry', $record->consent_expiry) }}">
        </div>

        <!-- 隲区ｱょ玄蛻・-->
        <div class="mb-3 d-flex">
          <label class="d-block mb-1 fw-bold">隲区ｱょ玄蛻・/label>
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <p>{{ $hasRecentRecords ? '邯咏ｶ・ : '譁ｰ隕・ }}</p>
          <input type="hidden" name="bill_category_id" value="{{ old('bill_category_id', $record->bill_category_id) }}">
        </div>

        <!-- 譁ｽ陦灘ｮ滓律謨ｰ -->
        <div class="mb-3 d-flex">
          <label class="d-block mb-1 fw-bold">譁ｽ陦灘ｮ滓律謨ｰ</label>
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <p id="therapy-days-display">{{ count($originalDates) }}譌･</p>
        </div>

        <!-- 鞫倩ｦ・-->
        <div class="mb-3">
          <label for="abstract" class="d-block mb-1 fw-bold">鞫倩ｦ・/label>
          <textarea id="abstract" name="abstract" rows="3" class="w-100">{{ old('abstract', $record->abstract) }}</textarea>
        </div>

        <button type="submit">逋ｻ骭ｲ</button>
      </div>
    </div>
  </form>

  @push('scripts')
  <script>
    // PHP螟画焚繧谷avaScript縺ｫ貂｡縺・    window.recordsConfig = {
      closedDays: @json($closedDays),
      selectedUserId: @json($record->clinic_user_id),
      oldInput: @json(session('_old_input', [])),
      errors: @json($errors->any()),
      originalDates: @json($originalDates),
      originalDistances: @json($originalDistances),
      userSearchUrl: ''
    };

    // 隍・｣ｽ繝｢繝ｼ繝峨・蝣ｴ蜷医∝・譛溯｡ｨ遉ｺ縺吶ｋ蟷ｴ譛医ｒ險ｭ螳・    if (window.recordsConfig.originalDates.length > 0) {
      const firstDate = new Date(window.recordsConfig.originalDates[0]);
      window.recordsConfig.initialYear = firstDate.getFullYear();
      window.recordsConfig.initialMonth = firstDate.getMonth() + 1;
    } else {
      window.recordsConfig.initialYear = new Date().getFullYear();
      window.recordsConfig.initialMonth = new Date().getMonth() + 1;
    }
  </script>
  <script src="{{ asset('js/utility.js') }}"></script>
  <script src="{{ asset('js/records.js') }}"></script>
  @endpush
</x-app-layout>
