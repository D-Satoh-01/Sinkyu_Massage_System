<!-- resources/views/records/records_index.blade.php -->


<x-app-layout>
  <x-page-header
    :title="$page_header_title"
    :breadcrumbs="App\Support\Breadcrumbs::generate('records.index')"
  />

  <!-- 利用者選択フォーム -->
  <form method="GET" action="{{ route('records.index') }}" id="filterForm">
    <div style="margin-bottom: 20px;">
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
    <div style="padding: 20px; text-align: center; font-size: 1.2em; color: #666;">
      利用者を選択してください
    </div>
  @else
  <form id="recordForm" method="POST" action="{{ route('records.store') }}">
    @csrf
    <input type="hidden" name="clinic_user_id" value="{{ $selectedUserId }}">

    <div style="display: flex; gap: 20px; align-items: flex-start;">
      <!-- カレンダー -->
      <div style="width: 15rem; text-align: center;">
        <select id="calendar-title"></select>
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
        <button type="button" id="clear-selection-btn" style="margin-top: 0.5rem">選択解除</button>
      </div>

      <div class="vr border border-black border-1 mx-3"></div>

      <!-- 実績フィールド -->
      <div style="flex: 3;">
        <!-- 施術種類 -->
        <div style="margin-bottom: 15px;">
          <label class="fw-semibold">施術種類</label>
          @error('therapy_type')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <br>
          <div style="margin-bottom: 10px;">
            <label><input type="radio" name="therapy_type" value="1" id="therapy_type_acupuncture"> はり･きゅう</label>
            <label style="margin-left: 20px;"><input type="radio" name="therapy_type" value="2" id="therapy_type_massage"> あんま･マッサージ</label>
          </div>

          <!-- 身体部位チェックボックス（あんま･マッサージ選択時のみ表示） -->
          <div id="bodyparts-section" style="display: none; margin-left: 20px;">
            <label><input type="checkbox" name="bodyparts[]" value="1"> 躯幹</label><br>
            <label><input type="checkbox" name="bodyparts[]" value="2"> 右上肢</label><br>
            <label><input type="checkbox" name="bodyparts[]" value="3"> 左上肢</label><br>
            <label><input type="checkbox" name="bodyparts[]" value="4"> 右下肢</label><br>
            <label><input type="checkbox" name="bodyparts[]" value="5"> 左下肢</label>
          </div>
        </div>

        <!-- 施術区分 -->
        <div style="margin-bottom: 15px;">
          <label class="fw-semibold">施術区分</label>
          @error('therapy_category')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <br>
          <label><input type="radio" name="therapy_category" value="1" id="therapy_category_visit"> 通院</label>
          <label style="margin-left: 20px;"><input type="radio" name="therapy_category" value="2" id="therapy_category_housecall"> 往療</label>
        </div>

        <!-- 往療距離（往療選択時のみ表示） -->
        <div id="housecall-distance-section" style="display: none; margin-bottom: 15px;">
          <label style="display: block; margin-bottom: 5px; font-weight: bold;">往療距離</label>
          <p style="margin: 5px 0; font-size: 0.9em; color: #666;">往療料が発生する場合は往療距離 (km) を入力（往療料無しなら0を入力）</p>
          <div id="housecall-distance-inputs"></div>
          <div style="margin-top: 10px;">
            上記日付を全て <input type="number" id="bulk-distance" step="0.1" min="0" style="width: 80px;"> km に
            <button type="button" id="apply-bulk-distance">変更</button>
          </div>
        </div>

        <!-- 開始時刻 -->
        <div style="margin-bottom: 15px;">
          <label class="fw-semibold" for="start_time">開始時刻</label>
          @error('start_time')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <br>
          <input type="time" id="start_time" name="start_time">
        </div>

        <!-- 終了時刻 -->
        <div style="margin-bottom: 15px;">
          <label class="fw-semibold" for="end_time">終了時刻</label>
          @error('end_time')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <br>
          <input type="time" id="end_time" name="end_time">
        </div>

        <!-- 施術内容 -->
        <div style="margin-bottom: 15px;">
          <label class="fw-semibold" for="therapy_content_id">施術内容</label>
          @error('therapy_content_id')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <br>
          <select id="therapy_content_id" name="therapy_content_id">
            <option value="">選択してください</option>
            @foreach($therapyContents as $content)
              <option value="{{ $content->id }}">{{ $content->therapy_content }}</option>
            @endforeach
          </select>

          <!-- 複製チェックボックス（あんま･マッサージ選択時のみ表示） -->
          <div id="therapy-content-duplication" style="display: none; margin-top: 10px; margin-left: 20px;">
            <label><input type="checkbox" name="duplicate_massage" value="1"> マッサージを同一内容で複製する</label><br>
            <label><input type="checkbox" name="duplicate_warm_compress" value="1"> 温罨法を同一内容で複製する</label><br>
            <label><input type="checkbox" name="duplicate_warm_electric" value="1"> 温罨法・電気光線器具を同一内容で複製する</label><br>
            <label><input type="checkbox" name="duplicate_manual_correction" value="1"> 変形徒手矯正術を同一内容で複製する</label>
          </div>
        </div>

        <!-- 施術者 -->
        <div style="margin-bottom: 15px;">
          <label class="fw-semibold" for="therapist_id">施術者</label>
          @error('therapist_id')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <br>
          <select id="therapist_id" name="therapist_id">
            <option value="">選択してください</option>
            @foreach($therapists as $therapist)
              <option value="{{ $therapist->id }}">{{ $therapist->therapist_name }} @if($therapist->furigana)({{ $therapist->furigana }})@endif</option>
            @endforeach
          </select>
        </div>

        <!-- 保険区分 -->
        <div style="margin-bottom: 15px;">
          <label class="fw-semibold">保険区分</label>
          @error('insurance_category')
            <span class="text-danger ms-2">{{ $message }}</span>
          @enderror
          <br>
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
                @endphp
                <option value="{{ $insurance->id }}">{{ $insuranceType }} (期限：{{ $expiryDate }})</option>
              @endforeach
            </select>
          @else
            <p style="color: #999;">保険情報が登録されていません</p>
          @endif
        </div>

        <!-- 同意有効期限 -->
        <div style="margin-bottom: 15px;">
          <label style="display: block; margin-bottom: 5px; font-weight: bold;">同意有効期限</label>
          <div id="consent-expiry-display">
            <span id="consent-expiry-acupuncture" style="display: none;">
              @if($consentsAcupuncture && $consentsAcupuncture->consenting_end_date)
                {{ date('Y/m/d', strtotime($consentsAcupuncture->consenting_end_date)) }}
              @else
                未設定
              @endif
            </span>
            <span id="consent-expiry-massage" style="display: none;">
              @if($consentsMassage && $consentsMassage->consenting_end_date)
                {{ date('Y/m/d', strtotime($consentsMassage->consenting_end_date)) }}
              @else
                未設定
              @endif
            </span>
          </div>
          <input type="hidden" name="consent_expiry" id="consent_expiry">
        </div>

        <!-- 請求区分 -->
        <div style="margin-bottom: 15px;">
          <label style="display: block; margin-bottom: 5px; font-weight: bold;">請求区分</label>
          <p>{{ $hasRecentRecords ? '継続' : '新規' }}</p>
          <input type="hidden" name="bill_category_id" value="{{ $hasRecentRecords ? 2 : 1 }}">
        </div>

        <!-- 施術実日数 -->
        <div style="margin-bottom: 15px;">
          <label style="display: block; margin-bottom: 5px; font-weight: bold;">施術実日数</label>
          <p id="therapy-days-display">0日</p>
        </div>

        <!-- 摘要 -->
        <div style="margin-bottom: 15px;">
          <label for="abstract" style="display: block; margin-bottom: 5px; font-weight: bold;">摘要</label>
          <textarea id="abstract" name="abstract" rows="3" style="width: 100%;"></textarea>
        </div>

        <button type="submit">登録</button>
      </div>
    </div>
  </form>
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

    // グローバル変数
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth(); // 0-11
    let selectedDates = [];

    // 初期化
    document.addEventListener('DOMContentLoaded', function() {
      if (selectedUserId) {
        initializeMonthSelect();
        renderCalendar(currentYear, currentMonth);
        setupEventListeners();
        setupFormEventListeners();
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
        const calendarText = `${year}年 ${String(month + 1).padStart(2, '0')}月`;

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

    // フォーム関連のイベントリスナーを設定
    function setupFormEventListeners() {
      // 施術種類の変更イベント
      const therapyTypeRadios = document.querySelectorAll('input[name="therapy_type"]');
      therapyTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
          const bodypartsSection = document.getElementById('bodyparts-section');
          const therapyContentDuplication = document.getElementById('therapy-content-duplication');
          const consentExpiryAcupuncture = document.getElementById('consent-expiry-acupuncture');
          const consentExpiryMassage = document.getElementById('consent-expiry-massage');
          const consentExpiryInput = document.getElementById('consent_expiry');

          if (this.value === '2') { // あんま･マッサージ
            bodypartsSection.style.display = 'block';
            therapyContentDuplication.style.display = 'block';
            consentExpiryAcupuncture.style.display = 'none';
            consentExpiryMassage.style.display = 'block';
            consentExpiryInput.value = consentExpiryMassage.textContent.trim();
          } else { // はり･きゅう
            bodypartsSection.style.display = 'none';
            therapyContentDuplication.style.display = 'none';
            consentExpiryAcupuncture.style.display = 'block';
            consentExpiryMassage.style.display = 'none';
            consentExpiryInput.value = consentExpiryAcupuncture.textContent.trim();
          }
        });
      });

      // 施術区分の変更イベント
      const therapyCategoryRadios = document.querySelectorAll('input[name="therapy_category"]');
      therapyCategoryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
          const housecallDistanceSection = document.getElementById('housecall-distance-section');
          if (this.value === '2') { // 往療
            housecallDistanceSection.style.display = 'block';
            updateHousecallDistanceInputs();
          } else { // 通院
            housecallDistanceSection.style.display = 'none';
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
        inputGroup.style.marginBottom = '5px';

        const label = document.createElement('span');
        label.textContent = '・' + formattedDate + '：';

        const input = document.createElement('input');
        input.type = 'number';
        input.name = `housecall_distance[${date}]`;
        input.className = 'housecall-distance-input';
        input.step = '0.1';
        input.min = '0';
        input.style.width = '80px';
        input.style.marginLeft = '5px';
        input.value = '0';

        const unit = document.createElement('span');
        unit.textContent = ' km';

        inputGroup.appendChild(label);
        inputGroup.appendChild(input);
        inputGroup.appendChild(unit);
        container.appendChild(inputGroup);
      });
    }
  </script>
  @endpush
</x-app-layout>
