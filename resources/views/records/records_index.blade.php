<!-- resources/views/records/records_index.blade.php -->


<x-app-layout>
  <h2>実績データ</h2><br>

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

  <form id="recordForm" method="POST" action="">
    @csrf

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

      <div style="flex: 0; align-self: stretch;">
        <div class="divider-vertical"></div>
      </div>

      <!-- 実績フィールド -->
      <div style="flex: 3;">
        <div>
          <label for="clinic_user_id">利用者ID</label>
          <input type="number" id="clinic_user_id" name="clinic_user_id">
        </div>

        <div>
          <label for="start_time">開始時刻</label>
          <input type="time" id="start_time" name="start_time">
        </div>

        <div>
          <label for="end_time">終了時刻</label>
          <input type="time" id="end_time" name="end_time">
        </div>

        <div>
          <label for="therapy_type">施術種類 <span style="color: red;">*</span></label>
          <select id="therapy_type" name="therapy_type" required>
            <option value="">選択してください</option>
            <option value="massage">あんま・マッサージ</option>
            <option value="acupuncture">鍼灸</option>
            <option value="self">自費</option>
          </select>
        </div>

        <div>
          <label for="therapy_category">施術区分</label>
          <input type="text" id="therapy_category" name="therapy_category">
        </div>

        <div>
          <label for="insurance_category">保険区分</label>
          <input type="text" id="insurance_category" name="insurance_category">
        </div>

        <div>
          <label for="housecall_distance">往療距離 (km)</label>
          <input type="number" id="housecall_distance" name="housecall_distance" step="0.1" min="0">
        </div>

        <div>
          <label for="therapy_days">施術日数</label>
          <input type="number" id="therapy_days" name="therapy_days" min="0">
        </div>

        <div>
          <label for="consent_expiry">同意書有効期限</label>
          <input type="date" id="consent_expiry" name="consent_expiry">
        </div>

        <div>
          <label for="therapy_content_id">施術内容ID</label>
          <input type="number" id="therapy_content_id" name="therapy_content_id">
        </div>

        <div>
          <label for="bill_category_id">請求区分ID</label>
          <input type="number" id="bill_category_id" name="bill_category_id">
        </div>

        <div>
          <label for="therapist_id">施術者ID</label>
          <input type="number" id="therapist_id" name="therapist_id">
        </div>

        <div>
          <label for="abstract">摘要</label>
          <textarea id="abstract" name="abstract"></textarea>
        </div><br>

        <button type="submit">登録</button>
      </div>
    </div>
  </form>

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

    // グローバル変数
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth(); // 0-11
    let selectedDates = [];

    // 初期化
    document.addEventListener('DOMContentLoaded', function() {
      initializeMonthSelect();
      renderCalendar(currentYear, currentMonth);
      setupEventListeners();
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
      });

      // 選択解除ボタン
      document.getElementById('clear-selection-btn').addEventListener('click', function() {
        selectedDates = [];
        const selectedElements = document.querySelectorAll('.calendar-day.selected');
        selectedElements.forEach(el => el.classList.remove('selected'));
      });
    }
  </script>
  @endpush
</x-app-layout>
