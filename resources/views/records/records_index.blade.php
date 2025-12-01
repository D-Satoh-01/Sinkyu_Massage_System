<!-- resources/views/records/records_index.blade.php -->


<x-app-layout>
  <x-page-header
    :title="$page_header_title"
    :breadcrumbs="App\Support\Breadcrumbs::generate('records.index')"
  />

  <!-- 利用者選択フォーム -->
  <form method="GET" action="{{ route('records.index') }}" id="filterForm">
    <div class="mb-3">
      <button type="button" onclick="openUserSearchPopup()">利用者検索</button>
      <label for="clinic_user_id"></label>
      <select name="clinic_user_id" id="clinic_user_id" onchange="document.getElementById('filterForm').submit();">
        <option value="">╌╌╌</option>
        @foreach($clinicUsers as $user)
          <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
            {{ $user->last_name }} {{ $user->first_name }} ({{ $user->last_kana }} {{ $user->first_kana }})
          </option>
        @endforeach
      </select>
    </div>
  </form>
  <br>

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

  @if(!$selectedUserId)
    <div class="p-4 text-center fs-5 text-secondary">
      利用者を選択してください
    </div>
  @else
  <form id="recordForm" method="POST" action="{{ route('records.store') }}">
    @csrf
    <input type="hidden" name="clinic_user_id" value="{{ $selectedUserId }}">

    <div class="d-flex gap-3 align-items-start">
      <!-- カレンダー -->
      <div class="text-center position-relative" style="width: 15rem;">
        <div id="calendar-title-display" class="fs-4 fw-bold py-1 d-inline-block" style="cursor: default;"></div>
        <select id="calendar-title" class="position-absolute top-0 start-50 translate-middle-x opacity-0" style="cursor: pointer; font-size: 1.5rem; padding: 0.2em 0em; border: none; background: transparent;"></select>
        <div class="calendar" id="calendar">
          <!-- 曜日ヘッダー -->
          <div class="calendar-day-header sunday">日</div>
          <div class="calendar-day-header">月</div>
          <div class="calendar-day-header">火</div>
          <div class="calendar-day-header">水</div>
          <div class="calendar-day-header">木</div>
          <div class="calendar-day-header">金</div>
          <div class="calendar-day-header saturday">土</div>
        </div>
        <button type="button" id="clear-selection-btn" class="mt-2">選択解除</button>
      </div>

      <div class="vr border border-black border-1 mx-3"></div>

      <!-- 実績フィールド -->
      <div class="flex-grow-1">
        <!-- 施術種類 -->
        <div class="d-flex">
          <label class="fw-semibold">施術種類</label>
          @error('therapy_type')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-2 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <div>
            <label><input type="radio" name="therapy_type" value="1" id="therapy_type_acupuncture" {{ old('therapy_type') == '1' ? 'checked' : '' }}>はり･きゅう</label>
            <label class="ms-3"><input type="radio" name="therapy_type" value="2" id="therapy_type_massage" {{ old('therapy_type') == '2' ? 'checked' : '' }}>あんま･マッサージ</label>
          </div>
        </div>
        <div class="mb-3">
          <!-- 身体部位チェックボックス（あんま･マッサージ選択時のみ表示） -->
          <div id="bodyparts-section" class="d-none">
            <label class="fw-semibold">　　部位</label>
            <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
            <label><input type="checkbox" name="bodyparts[]" value="1" {{ in_array('1', old('bodyparts', [])) ? 'checked' : '' }}> 躯幹</label>
            <label><input type="checkbox" name="bodyparts[]" value="2" {{ in_array('2', old('bodyparts', [])) ? 'checked' : '' }}> 右上肢</label>
            <label><input type="checkbox" name="bodyparts[]" value="3" {{ in_array('3', old('bodyparts', [])) ? 'checked' : '' }}> 左上肢</label>
            <label><input type="checkbox" name="bodyparts[]" value="4" {{ in_array('4', old('bodyparts', [])) ? 'checked' : '' }}> 右下肢</label>
            <label><input type="checkbox" name="bodyparts[]" value="5" {{ in_array('5', old('bodyparts', [])) ? 'checked' : '' }}> 左下肢</label>
            </div>
        </div>

        <!-- 施術区分 -->
        <div class="mb-3">
          <label class="fw-semibold">施術区分</label>
          @error('therapy_category')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <label><input type="radio" name="therapy_category" value="1" id="therapy_category_visit" {{ old('therapy_category') == '1' ? 'checked' : '' }}> 通院</label>
          <label class="ms-3"><input type="radio" name="therapy_category" value="2" id="therapy_category_housecall" {{ old('therapy_category') == '2' ? 'checked' : '' }}> 往療</label>
        </div>

        <!-- 往療距離（往療選択時のみ表示） -->
        <div id="housecall-distance-section" class="d-none mb-3">
          <label class="d-block mb-1 fw-bold">往療距離</label>
          <p class="my-1 small text-secondary">往療料が発生する場合は往療距離を入力（往療料無しなら0を入力）</p>
          <div id="housecall-distance-inputs"></div>
          <div class="mt-2">
            上記日付を全て <input type="number" id="bulk-distance" step="0.5" min="0" style="width: 80px;"> km に
            <button type="button" id="apply-bulk-distance">変更</button>
          </div>
        </div>

        <!-- 開始時刻 & 終了時刻 -->
        <div class="mb-3">
          <label class="fw-semibold" for="start_time">開始時刻</label>
          @error('start_time')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}">
        </div>

        <div class="mb-3">
          <label class="fw-semibold" for="end_time">終了時刻</label>
          @error('end_time')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}">
        </div>

        <!-- 施術内容 -->
        <div class="mb-3">
          <label class="fw-semibold" for="therapy_content_id">施術内容</label>
          @error('therapy_content_id')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <select id="therapy_content_id" name="therapy_content_id">
            <option value="">選択してください</option>
            @foreach($therapyContents as $content)
              <option value="{{ $content->id }}" {{ old('therapy_content_id') == $content->id ? 'selected' : '' }}>{{ $content->therapy_content }}</option>
            @endforeach
          </select>

          <!-- 複製チェックボックス（あんま･マッサージ選択時のみ表示） -->
          <div id="therapy-content-duplication" class="d-none mt-2 ms-3">
            <label><input type="checkbox" name="duplicate_massage" value="1" {{ old('duplicate_massage') ? 'checked' : '' }}> マッサージを同一内容で複製する</label><br>
            <label><input type="checkbox" name="duplicate_warm_compress" value="1" {{ old('duplicate_warm_compress') ? 'checked' : '' }}> 温罨法を同一内容で複製する</label><br>
            <label><input type="checkbox" name="duplicate_warm_electric" value="1" {{ old('duplicate_warm_electric') ? 'checked' : '' }}> 温罨法・電気光線器具を同一内容で複製する</label><br>
            <label><input type="checkbox" name="duplicate_manual_correction" value="1" {{ old('duplicate_manual_correction') ? 'checked' : '' }}> 変形徒手矯正術を同一内容で複製する</label>
          </div>
        </div>

        <!-- 施術者 -->
        <div class="mb-3">
          <label class="fw-semibold" for="therapist_id">施術者</label>
          @error('therapist_id')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <select id="therapist_id" name="therapist_id">
            <option value="">選択してください</option>
            @foreach($therapists as $therapist)
              <option value="{{ $therapist->id }}" {{ old('therapist_id') == $therapist->id ? 'selected' : '' }}>{{ $therapist->therapist_name }} @if($therapist->furigana)({{ $therapist->furigana }})@endif</option>
            @endforeach
          </select>
        </div>

        <!-- 保険区分 -->
        <div class="mb-3">
          <label class="fw-semibold">保険区分</label>
          @error('insurance_category')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          @if($insurances && $insurances->count() > 0)
            <select name="insurance_category">
              <option value="">選択してください</option>
              @foreach($insurances as $insurance)
                @php
                  $insurerNumberLength = strlen($insurance->insurer_number ?? '');
                  $insuranceType = '';
                  if($insurerNumberLength == 6) {
                    $insuranceType = '国民健康保険';
                  } elseif($insurerNumberLength == 8) {
                    $insuranceType = '組合保険';
                  } else {
                    $insuranceType = '保険';
                  }
                  $expiryDate = $insurance->expiry_date ? date('Y/m/d', strtotime($insurance->expiry_date)) : '未設定';
                  $isSelected = old('insurance_category') ? old('insurance_category') == $insurance->id : $latestInsuranceId == $insurance->id;
                @endphp
                <option value="{{ $insurance->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $insuranceType }}（期限：{{ $expiryDate }}）</option>
              @endforeach
            </select>
          @else
            <p class="text-secondary">保険情報が登録されていません</p>
          @endif
        </div>

        <!-- 同意有効期限 -->
        <div class="mb-3 d-flex">
          <label class="mb-1 fw-bold">同意有効期限</label>
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <div id="consent-expiry-display">
            <span id="consent-expiry-acupuncture" class="d-none">
              @if($consentsAcupuncture && $consentsAcupuncture->consenting_end_date)
                {{ date('Y/m/d', strtotime($consentsAcupuncture->consenting_end_date)) }}
              @else
                未登録
              @endif
            </span>
            <span id="consent-expiry-massage" class="d-none">
              @if($consentsMassage && $consentsMassage->consenting_end_date)
                {{ date('Y/m/d', strtotime($consentsMassage->consenting_end_date)) }}
              @else
                未登録
              @endif
            </span>
          </div>
          <input type="hidden" name="consent_expiry" id="consent_expiry">
        </div>

        <!-- 請求区分 -->
        <div class="mb-3 d-flex">
          <label class="d-block mb-1 fw-bold">請求区分</label>
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <p>{{ $hasRecentRecords ? '継続' : '新規' }}</p>
          <input type="hidden" name="bill_category_id" value="{{ $hasRecentRecords ? 2 : 1 }}">
        </div>

        <!-- 施術実日数 -->
        <div class="mb-3 d-flex">
          <label class="d-block mb-1 fw-bold">施術実日数</label>
          <div class="vr ms-1 me-2" style="height: 1.4rem; position: relative; top: 0.3rem;"></div>
          <p id="therapy-days-display">0日</p>
        </div>

        <!-- 摘要 -->
        <div class="mb-3">
          <label for="abstract" class="d-block mb-1 fw-bold">摘要</label>
          <textarea id="abstract" name="abstract" rows="3" class="w-100">{{ old('abstract') }}</textarea>
        </div>

        <button type="submit">登録</button>
      </div>
    </div>
  </form>

  <hr class="m-0 mt-5 mb-3">

  <!-- 実績データ一覧テーブル -->
  @if($selectedUserId && $records->count() > 0)
    <div>
      <p class="mb-3">{{ $selectedYear }}年 {{ sprintf('%02d', $selectedMonth) }}月 の実績データ</p>

      <div class="mb-3">
        <button type="button" class="btn btn-primary me-2">はり･きゅう支給申請書印刷</button>
        <button type="button" class="btn btn-primary">あんま･マッサージ支給申請書印刷</button>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th rowspan="3" class="align-middle text-center" style="min-width: 200px;">[編集]</th>
              <th rowspan="3" class="align-middle text-center" style="min-width: 150px;">施術内容 / 施術者 / 時刻</th>
              <th rowspan="3" class="align-middle text-center" style="min-width: 150px;">登録日時 / 更新日時</th>
              <th colspan="{{ date('t', strtotime("$selectedYear-$selectedMonth-01")) }}" class="text-center">施術日</th>
            </tr>
            <tr>
              @php
                $daysInMonth = date('t', strtotime("$selectedYear-$selectedMonth-01"));
              @endphp
              @for($day = 1; $day <= $daysInMonth; $day++)
                <th class="text-center" style="min-width: 30px;">{{ $day }}</th>
              @endfor
            </tr>
            <tr>
              @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                  $date = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                  $dayOfWeek = date('w', strtotime($date));
                  $dayClass = '';
                  if ($dayOfWeek == 0) {
                    $dayClass = 'text-danger'; // 日曜日
                  } elseif ($dayOfWeek == 6) {
                    $dayClass = 'text-primary'; // 土曜日
                  }
                  $dayNames = ['日', '月', '火', '水', '木', '金', '土'];
                @endphp
                <th class="text-center {{ $dayClass }}" style="min-width: 30px;">{{ $dayNames[$dayOfWeek] }}</th>
              @endfor
            </tr>
          </thead>
          <tbody>
            @foreach($records as $record)
              <tr>
                <td>
                  <button type="button" class="btn btn-sm btn-secondary mb-1">[編集]</button><br>
                  <button type="button" class="btn btn-sm btn-info mb-1">[当月へ複製]</button><br>
                  <button type="button" class="btn btn-sm btn-info mb-1">[翌月へ複製]</button><br>
                  <button type="button" class="btn btn-sm btn-danger">[削除]</button>
                </td>
                <td>
                  {{ $record->therapy_content ?? '未設定' }}<br>
                  {{ $record->therapist_name ?? '未設定' }}<br>
                  {{ $record->start_time ? date('H:i', strtotime($record->start_time)) : '--:--' }} ~ {{ $record->end_time ? date('H:i', strtotime($record->end_time)) : '--:--' }}
                </td>
                <td class="small">
                  {{ date('Y/m/d H:i', strtotime($record->created_at)) }}<br>
                  {{ date('Y/m/d H:i', strtotime($record->updated_at)) }}
                </td>
                @for($day = 1; $day <= $daysInMonth; $day++)
                  @php
                    $currentDate = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                    $hasRecord = in_array($currentDate, $record->dates);
                    $mark = '';
                    if ($hasRecord) {
                      // 通院なら○、往療なら◎
                      $mark = $record->therapy_category == 1 ? '○' : '◎';
                    }
                  @endphp
                  <td class="text-center">{{ $mark }}</td>
                @endfor
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
  @endif

  @push('scripts')
  <script>
    // 利用者検索ポップアップを開く
    function openUserSearchPopup() {
      const width = 600;
      const height = 400;
      const left = (screen.width - width) / 2;
      const top = (screen.height - height) / 2;
      window.open(
        '{{ route("user.search") }}',
        'userSearchPopup',
        `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
      );
    }

    // PHP変数をJavaScriptに渡す
    const closedDays = @json($closedDays);
    const selectedUserId = @json($selectedUserId);
    const oldInput = @json(session('_old_input', []));
    const errors = @json($errors->any());
    const initialYear = @json($selectedYear);
    const initialMonth = @json($selectedMonth);

    // グローバル変数
    let currentYear = initialYear || new Date().getFullYear();
    let currentMonth = initialMonth ? initialMonth - 1 : new Date().getMonth(); // 0-11
    let selectedDates = [];

    // 初期化
    document.addEventListener('DOMContentLoaded', function() {
      if (selectedUserId) {
        initializeMonthSelect();
        renderCalendar(currentYear, currentMonth);
        setupEventListeners();
        setupFormEventListeners();
        updateCalendarTitleDisplay();
        restoreOldInput();
        updateConsentExpiryDisplay(); // 初期状態で同意有効期限を表示
      }
    });

    // カレンダータイトルセレクトボックスの初期化
    function initializeMonthSelect() {
      const calendarTitleSelect = document.getElementById('calendar-title');
      const startYear = 2020;
      const startMonth = 0; // 1月 (0-based)
      const now = new Date();

      // 6ヶ月後の年月を計算
      const futureDate = new Date(now.getFullYear(), now.getMonth() + 6, 1);
      const endYear = futureDate.getFullYear();
      const endMonth = futureDate.getMonth();

      let year = endYear;
      let month = endMonth;

      const currentYearMonth = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

      while (year > startYear || (year === startYear && month >= startMonth)) {
        const value = `${year}-${String(month + 1).padStart(2, '0')}`;
        const calendarText = `${year} ｰ ${String(month + 1).padStart(2, '0')}`;

        // カレンダータイトルセレクトボックス
        const option = document.createElement('option');
        option.value = value;
        option.textContent = calendarText;
        if (value === currentYearMonth) {
          option.selected = true;
        }
        calendarTitleSelect.appendChild(option);

        month--;
        if (month < 0) {
          month = 11;
          year--;
        }
      }
    }

    // カレンダーを描画
    function renderCalendar(year, month) {
      const calendar = document.getElementById('calendar');
      const calendarTitleSelect = document.getElementById('calendar-title');

      // タイトルセレクトボックスを更新
      const value = `${year}-${String(month + 1).padStart(2, '0')}`;
      calendarTitleSelect.value = value;

      // カレンダーの日付部分をクリア（曜日ヘッダーは残す）
      const existingDays = calendar.querySelectorAll('.calendar-day');
      existingDays.forEach(day => day.remove());

      // 月の初日と最終日を取得
      const firstDay = new Date(year, month, 1);
      const lastDay = new Date(year, month + 1, 0);
      const firstDayOfWeek = firstDay.getDay(); // 0 (日曜) - 6 (土曜)
      const daysInMonth = lastDay.getDate();

      // 前月の日付を埋める
      const prevMonthLastDay = new Date(year, month, 0).getDate();
      for (let i = firstDayOfWeek - 1; i >= 0; i--) {
        const day = prevMonthLastDay - i;
        const dayElement = createDayElement(day, true, -1);
        calendar.appendChild(dayElement);
      }

      // 当月の日付を埋める
      for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        const dayOfWeek = date.getDay();
        const isClosed = isClosedDay(dayOfWeek);
        const dayElement = createDayElement(day, false, dayOfWeek, isClosed);
        calendar.appendChild(dayElement);
      }

      // 次月の日付を埋める（6週分になるよう調整）
      const totalCells = calendar.children.length - 7; // 曜日ヘッダーを除く
      const remainingCells = 42 - totalCells;
      for (let day = 1; day <= remainingCells; day++) {
        const dayElement = createDayElement(day, true, -1);
        calendar.appendChild(dayElement);
      }
    }

    // 日付要素を作成
    function createDayElement(day, isOtherMonth, dayOfWeek, isClosed = false) {
      const dayElement = document.createElement('div');
      dayElement.classList.add('calendar-day');
      dayElement.textContent = day;

      if (isOtherMonth) {
        dayElement.classList.add('other-month');
      } else if (isClosed) {
        dayElement.classList.add('closed');
      } else {
        // クリック可能な日付
        dayElement.dataset.date = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        // 日曜・土曜の色分け
        if (dayOfWeek === 0) {
          dayElement.classList.add('sunday');
        } else if (dayOfWeek === 6) {
          dayElement.classList.add('saturday');
        }

        // クリックイベント
        dayElement.addEventListener('click', function() {
          toggleDateSelection(this);
        });
      }

      return dayElement;
    }

    // 定休日かどうかを判定
    function isClosedDay(dayOfWeek) {
      const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
      return closedDays[dayNames[dayOfWeek]] === 1;
    }

    // 日付選択のトグル
    function toggleDateSelection(dayElement) {
      const date = dayElement.dataset.date;
      if (!date) return;

      if (dayElement.classList.contains('selected')) {
        dayElement.classList.remove('selected');
        const index = selectedDates.indexOf(date);
        if (index > -1) {
          selectedDates.splice(index, 1);
        }
      } else {
        dayElement.classList.add('selected');
        selectedDates.push(date);
      }
    }

    // イベントリスナーの設定
    function setupEventListeners() {
      // カレンダータイトルセレクトボックスの変更
      document.getElementById('calendar-title').addEventListener('change', function() {
        const selectedValue = this.value;
        const [year, month] = selectedValue.split('-').map(Number);
        currentYear = year;
        currentMonth = month - 1;
        selectedDates = []; // 選択をクリア
        renderCalendar(currentYear, currentMonth);
        updateTherapyDaysDisplay();
        updateHousecallDistanceInputs();
        updateCalendarTitleDisplay();

        // 実績データ一覧を更新（ページをリロード）
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('year', year);
        currentUrl.searchParams.set('month', month);
        window.location.href = currentUrl.toString();
      });

      // 選択解除ボタン
      document.getElementById('clear-selection-btn').addEventListener('click', function() {
        selectedDates = [];
        const selectedElements = document.querySelectorAll('.calendar-day.selected');
        selectedElements.forEach(el => el.classList.remove('selected'));
        updateTherapyDaysDisplay();
        updateHousecallDistanceInputs();
      });
    }

    // カレンダータイトル表示を更新
    function updateCalendarTitleDisplay() {
      const display = document.getElementById('calendar-title-display');
      const titleText = `${currentYear}年 ${String(currentMonth + 1).padStart(2, '0')}月`;
      display.textContent = titleText;
    }

    // 日付選択のトグル（オーバーライド）
    function toggleDateSelection(dayElement) {
      const date = dayElement.dataset.date;
      if (!date) return;

      if (dayElement.classList.contains('selected')) {
        dayElement.classList.remove('selected');
        const index = selectedDates.indexOf(date);
        if (index > -1) {
          selectedDates.splice(index, 1);
        }
      } else {
        dayElement.classList.add('selected');
        selectedDates.push(date);
      }

      // 施術実日数を更新
      updateTherapyDaysDisplay();
      // 往療距離入力欄を更新
      updateHousecallDistanceInputs();
    }

    // 同意有効期限の表示を更新
    function updateConsentExpiryDisplay() {
      const therapyTypeRadios = document.querySelectorAll('input[name="therapy_type"]');
      const checkedRadio = Array.from(therapyTypeRadios).find(radio => radio.checked);

      const bodypartsSection = document.getElementById('bodyparts-section');
      const therapyContentDuplication = document.getElementById('therapy-content-duplication');
      const consentExpiryAcupuncture = document.getElementById('consent-expiry-acupuncture');
      const consentExpiryMassage = document.getElementById('consent-expiry-massage');
      const consentExpiryInput = document.getElementById('consent_expiry');

      if (checkedRadio) {
        if (checkedRadio.value === '2') { // あんま･マッサージ
          bodypartsSection.classList.remove('d-none');
          therapyContentDuplication.classList.remove('d-none');
          consentExpiryAcupuncture.classList.add('d-none');
          consentExpiryMassage.classList.remove('d-none');
          const massageValue = consentExpiryMassage.textContent.trim();
          consentExpiryInput.value = massageValue === '未登録' ? '' : massageValue;
        } else { // はり･きゅう
          bodypartsSection.classList.add('d-none');
          therapyContentDuplication.classList.add('d-none');
          consentExpiryAcupuncture.classList.remove('d-none');
          consentExpiryMassage.classList.add('d-none');
          const acupunctureValue = consentExpiryAcupuncture.textContent.trim();
          consentExpiryInput.value = acupunctureValue === '未登録' ? '' : acupunctureValue;
        }
      }
    }

    // フォーム関連のイベントリスナーを設定
    function setupFormEventListeners() {
      // 施術種類の変更イベント
      const therapyTypeRadios = document.querySelectorAll('input[name="therapy_type"]');
      therapyTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
          updateConsentExpiryDisplay();
        });
      });

      // 施術区分の変更イベント
      const therapyCategoryRadios = document.querySelectorAll('input[name="therapy_category"]');
      therapyCategoryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
          const housecallDistanceSection = document.getElementById('housecall-distance-section');
          if (this.value === '2') { // 往療
            housecallDistanceSection.classList.remove('d-none');
            updateHousecallDistanceInputs();
          } else { // 通院
            housecallDistanceSection.classList.add('d-none');
          }
        });
      });

      // 往療距離一括変更ボタン
      document.getElementById('apply-bulk-distance').addEventListener('click', function() {
        const bulkDistance = document.getElementById('bulk-distance').value;
        const distanceInputs = document.querySelectorAll('.housecall-distance-input');
        distanceInputs.forEach(input => {
          input.value = bulkDistance;
        });
      });
    }

    // 施術実日数の表示を更新
    function updateTherapyDaysDisplay() {
      const display = document.getElementById('therapy-days-display');
      if (display) {
        display.textContent = selectedDates.length + '日';
      }
    }

    // 往療距離入力欄を更新
    function updateHousecallDistanceInputs() {
      const container = document.getElementById('housecall-distance-inputs');
      if (!container) return;

      container.innerHTML = '';

      if (selectedDates.length === 0) {
        return;
      }

      // 日付順にソート
      const sortedDates = [...selectedDates].sort();

      sortedDates.forEach(date => {
        const dateObj = new Date(date);
        const formattedDate = `${dateObj.getFullYear()}/${String(dateObj.getMonth() + 1).padStart(2, '0')}/${String(dateObj.getDate()).padStart(2, '0')}`;

        const inputGroup = document.createElement('div');
        inputGroup.className = 'mb-1';

        const label = document.createElement('span');
        label.textContent = '・' + formattedDate + '：';

        const input = document.createElement('input');
        input.type = 'number';
        input.name = `housecall_distance[${date}]`;
        input.className = 'housecall-distance-input ms-1';
        input.step = '0.5';
        input.min = '0';
        input.style.width = '80px';

        // 古い入力値を復元
        if (oldInput.housecall_distance && oldInput.housecall_distance[date] !== undefined) {
          input.value = oldInput.housecall_distance[date];
        } else {
          input.value = '0';
        }

        const unit = document.createElement('span');
        unit.textContent = ' km';

        inputGroup.appendChild(label);
        inputGroup.appendChild(input);
        inputGroup.appendChild(unit);
        container.appendChild(inputGroup);
      });
    }

    // 古い入力値を復元
    function restoreOldInput() {
      if (!errors || !oldInput || Object.keys(oldInput).length === 0) {
        return;
      }

      // カレンダーの選択状態を復元
      if (oldInput.housecall_distance && typeof oldInput.housecall_distance === 'object') {
        const dates = Object.keys(oldInput.housecall_distance);
        if (dates.length > 0) {
          // 最初の日付から年月を取得してカレンダーを表示
          const firstDate = new Date(dates[0]);
          currentYear = firstDate.getFullYear();
          currentMonth = firstDate.getMonth();
          renderCalendar(currentYear, currentMonth);
          updateCalendarTitleDisplay();

          // 日付を選択状態にする
          dates.forEach(date => {
            selectedDates.push(date);
            const dayElement = document.querySelector(`.calendar-day[data-date="${date}"]`);
            if (dayElement) {
              dayElement.classList.add('selected');
            }
          });
        }
      }

      // 施術種類の状態を復元して関連セクションを表示
      if (oldInput.therapy_type) {
        updateConsentExpiryDisplay();
      }

      // 施術区分の状態を復元して往療距離セクションを表示
      if (oldInput.therapy_category) {
        const therapyCategory = oldInput.therapy_category;
        if (therapyCategory === '2') {
          document.getElementById('housecall-distance-section').classList.remove('d-none');
        }
      }

      // 施術実日数と往療距離入力欄を更新
      updateTherapyDaysDisplay();
      updateHousecallDistanceInputs();
    }
  </script>
  @endpush
</x-app-layout>
