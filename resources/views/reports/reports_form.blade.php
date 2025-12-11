<!-- resources/views/reports/reports_form.blade.php -->


<x-app-layout>
  @php
    // 繝｢繝ｼ繝峨↓蠢懊§縺溘ヱ繝ｳ縺上★繝ｪ繧ｹ繝亥ｮ夂ｾｩ蜷阪ｒ豎ｺ螳・    if ($mode === 'create') {
      $breadcrumbName = 'reports.create';
    } elseif ($mode === 'edit') {
      $breadcrumbName = 'reports.edit';
    } else { // duplicate
      $breadcrumbName = 'reports.duplicate';
    }
  @endphp

  <x-page-header
    :title="$page_header_title"
    :breadcrumbs="App\Support\Breadcrumbs::generate($breadcrumbName)"
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

  @if($mode === 'create')
    <form method="POST" action="{{ route('reports.store') }}">
  @elseif($mode === 'edit')
    <form method="POST" action="{{ route('reports.update', $report->id) }}">
      @method('PUT')
  @elseif($mode === 'duplicate')
    <form method="POST" action="{{ route('reports.duplicate.store') }}">
  @endif
    @csrf
    <input type="hidden" name="clinic_user_id" value="{{ $clinicUserId }}">

    <div class="mb-4">
      @if($mode === 'duplicate')
        <!-- 隍・｣ｽ繝｢繝ｼ繝峨〒縺ｯ蟷ｴ譛医ｒ螟画峩蜿ｯ閭ｽ -->
        <div class="d-flex gap-3 align-items-center">
          <label class="fw-bold">隍・｣ｽ蜈亥ｹｴ譛・/label>
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <select id="duplicate-date-select" required>
              @php
                $currentDate = new DateTime();
                $maxDate = (clone $currentDate)->modify('+6 months');
                $maxYear = (int)$maxDate->format('Y');
                $maxMonth = (int)$maxDate->format('m');
              @endphp
              @for($y = $maxYear; $y >= 2000; $y--)
                @php
                  $startMonth = ($y == $maxYear) ? $maxMonth : 12;
                  $endMonth = ($y == 2000) ? 1 : 1;
                @endphp
                @for($m = $startMonth; $m >= $endMonth; $m--)
                  @php
                    $value = sprintf('%04d-%02d', $y, $m);
                    $isSelected = old('year', $year) == $y && old('month', $month) == $m;
                  @endphp
                  <option value="{{ $value }}" {{ $isSelected ? 'selected' : '' }}>{{ $y }}窶・ｰ窶{{ sprintf('%02d', $m) }}</option>
                @endfor
              @endfor
            </select>
          <input type="hidden" name="year" id="year" value="{{ old('year', $year) }}">
          <input type="hidden" name="month" id="month" value="{{ old('month', $month) }}">
        </div>
      @else
        <!-- 譁ｰ隕冗匳骭ｲ繝ｻ邱ｨ髮・Δ繝ｼ繝峨〒縺ｯ蟷ｴ譛医ｒ蝗ｺ螳夊｡ｨ遉ｺ -->
        <h5 class="fw-bold mb-3">{{ $year }}蟷ｴ窶{{ sprintf('%02d', $month) }}譛・縺ｮ蝣ｱ蜻頑嶌繝・・繧ｿ</h5>
        <input type="hidden" name="year" value="{{ $year }}">
        <input type="hidden" name="month" value="{{ $month }}">
      @endif
    </div>

    <div class="mb-3">
      <label for="subjective_symptom_and_wish" class="form-label fw-bold">荳ｻ隕ｳ逞・憾</label><br>
      <textarea id="subjective_symptom_and_wish" name="subjective_symptom_and_wish" class="w-100" rows="5" maxlength="1000">{{ old('subjective_symptom_and_wish', $report->subjective_symptom_and_wish ?? '') }}</textarea>
    </div>

    <div class="mb-3">
      <label for="objective_symptom" class="form-label fw-bold">螳｢隕ｳ逞・憾</label><br>
      <textarea id="objective_symptom" name="objective_symptom"  class="w-100" rows="5" maxlength="1000">{{ old('objective_symptom', $report->objective_symptom ?? '') }}</textarea>
    </div>

    <div class="mb-3">
      <label for="therapy_content" class="form-label fw-bold">譁ｽ陦灘・螳ｹ</label><br>
      <textarea id="therapy_content" name="therapy_content" class="w-100" rows="5" maxlength="1000">{{ old('therapy_content', $report->therapy_content ?? '') }}</textarea>
    </div>

    <div class="mb-3">
      <label for="therapy_plan" class="form-label fw-bold">豐ｻ逋りｨ育判</label><br>
      <textarea id="therapy_plan" name="therapy_plan" class="w-100" rows="5" maxlength="1000">{{ old('therapy_plan', $report->therapy_plan ?? '') }}</textarea>
    </div>

    <div class="d-flex gap-2 mt-4">
      <button type="button" onclick="window.location.href='{{ route('reports.index', ['clinic_user_id' => $clinicUserId, 'scroll_year' => $year, 'scroll_month' => $month]) }}'">繧ｭ繝｣繝ｳ繧ｻ繝ｫ</button>
      <button type="submit">逋ｻ骭ｲ</button>
    </div>
  </form>

  @push('scripts')
  <script src="{{ asset('js/utility.js') }}"></script>
  <script src="{{ asset('js/reports.js') }}"></script>
  @endpush
</x-app-layout>
