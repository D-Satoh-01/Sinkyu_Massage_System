// 実績データ管理画面

//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//
// グローバル変数
//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//
let currentYear = null;
let currentMonth = null;
window.selectedDates = new Set();


//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//
// カレンダー機能
//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//

// カレンダーを初期化
function initializeCalendar(initialYear, initialMonth) {
  currentYear = initialYear || new Date().getFullYear();
  currentMonth = initialMonth ? initialMonth - 1 : new Date().getMonth(); // 0-11

  initializeMonthSelect();
  renderCalendar(currentYear, currentMonth);
  updateCalendarTitleDisplay();
}

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
    const calendarText = `${year} ｰ ${String(month + 1).padStart(2, '0')}`;

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
  return window.recordsConfig.closedDays[dayNames[dayOfWeek]] === 1;
}

// 日付選択のトグル
function toggleDateSelection(dayElement) {
  const date = dayElement.dataset.date;
  if (!date) return;

  if (dayElement.classList.contains('selected')) {
    dayElement.classList.remove('selected');
    window.selectedDates.delete(date);
  } else {
    dayElement.classList.add('selected');
    window.selectedDates.add(date);
  }

  // 施術実日数を更新
  updateTherapyDaysDisplay();
  // 往療距離入力欄を更新
  updateHousecallDistanceInputs();
  // 実績フィールドの状態を更新
  updateRecordFieldsState();
}

// カレンダータイトル表示を更新
function updateCalendarTitleDisplay() {
  const display = document.getElementById('calendar-title-display');
  const titleText = `${currentYear}年 ${String(currentMonth + 1).padStart(2, '0')}月`;
  display.textContent = titleText;
}

// カレンダーの選択状態を復元
function restoreCalendarSelection(oldInput) {
  if (!oldInput || !oldInput.housecall_distance || typeof oldInput.housecall_distance !== 'object') {
    return;
  }

  const dates = Object.keys(oldInput.housecall_distance);
  if (dates.length === 0) return;

  // 最初の日付から年月を取得してカレンダーを表示
  const firstDate = new Date(dates[0]);
  currentYear = firstDate.getFullYear();
  currentMonth = firstDate.getMonth();
  renderCalendar(currentYear, currentMonth);
  updateCalendarTitleDisplay();

  // 日付を選択状態にする
  dates.forEach(date => {
    window.selectedDates.add(date);
    const dayElement = document.querySelector(`.calendar-day[data-date="${date}"]`);
    if (dayElement) {
      dayElement.classList.add('selected');
    }
  });
}

// 選択解除
function clearDateSelection() {
  window.selectedDates.clear();
  const selectedElements = document.querySelectorAll('.calendar-day.selected');
  selectedElements.forEach(el => el.classList.remove('selected'));
}

// カレンダー関連のイベントリスナーを設定
function setupCalendarEventListeners() {
  // カレンダータイトルセレクトボックスの変更
  document.getElementById('calendar-title').addEventListener('change', function() {
    const selectedValue = this.value;
    const [year, month] = selectedValue.split('-').map(Number);
    currentYear = year;
    currentMonth = month - 1;
    window.selectedDates.clear(); // 選択をクリア
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
    clearDateSelection();
    updateTherapyDaysDisplay();
    updateHousecallDistanceInputs();
    updateRecordFieldsState();
  });
}


//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//
// フォーム制御機能
//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//

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

// 施術実日数の表示を更新
function updateTherapyDaysDisplay() {
  const display = document.getElementById('therapy-days-display');
  if (display) {
    display.textContent = window.selectedDates.size + '日';
  }
}

// 往療距離入力欄を更新
function updateHousecallDistanceInputs() {
  const container = document.getElementById('housecall-distance-inputs');
  if (!container) return;

  container.innerHTML = '';

  if (window.selectedDates.size === 0) {
    return;
  }

  // 日付順にソート
  const sortedDates = [...window.selectedDates].sort();
  const oldInput = window.recordsConfig.oldInput || {};

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

// 実績フィールドの状態を更新（日付選択に応じて入力可/不可を切り替え）
function updateRecordFieldsState() {
  console.log('[Records] updateRecordFieldsState() 実行開始');
  const recordFields = document.getElementById('record-fields');
  if (!recordFields) {
    console.log('[Records] record-fields要素が見つかりません');
    return;
  }

  const hasSelectedDates = window.selectedDates.size > 0;
  console.log('[Records] 選択された日付数:', window.selectedDates.size);

  // すべての入力要素を取得
  const inputs = recordFields.querySelectorAll('input, select, textarea, button[type="submit"]');
  console.log('[Records] 対象入力要素数:', inputs.length);

  inputs.forEach(input => {
    if (hasSelectedDates) {
      // 日付が選択されている場合は入力可能
      input.disabled = false;
      input.style.cursor = '';
    } else {
      // 日付が選択されていない場合は入力不可
      input.disabled = true;
      input.style.cursor = 'default';
    }
  });
  
  console.log('[Records] disabled属性の設定完了、ツールチップ再初期化を呼び出し');
  // disabled属性の変更後にツールチップを再初期化
  if (typeof initializeReadonlyTooltips === 'function') {
    initializeReadonlyTooltips();
  } else {
    console.log('[Records] initializeReadonlyTooltips関数が見つかりません');
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

// 古い入力値を復元
function restoreOldInput() {
  const oldInput = window.recordsConfig.oldInput || {};
  const errors = window.recordsConfig.errors;

  if (!errors || !oldInput || Object.keys(oldInput).length === 0) {
    return;
  }

  // カレンダーの選択状態を復元
  restoreCalendarSelection(oldInput);

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


//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//
// 新規登録画面
//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//

// 利用者検索ポップアップを開く
function openUserSearchPopup() {
  const width = 600;
  const height = 400;
  const left = (screen.width - width) / 2;
  const top = (screen.height - height) / 2;
  window.open(
    window.recordsConfig.userSearchUrl,
    'userSearchPopup',
    `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
  );
}

// 新規登録画面の初期化
function initializeIndexPage() {
  if (window.recordsConfig.selectedUserId) {
    initializeCalendar(window.recordsConfig.initialYear, window.recordsConfig.initialMonth);
    setupCalendarEventListeners();
    setupFormEventListeners();
    restoreOldInput();
    updateConsentExpiryDisplay(); // 初期状態で同意有効期限を表示
    updateRecordFieldsState(); // 初期状態で実績フィールドの状態を更新
    
    // disabled属性が設定された後にツールチップを初期化
    if (typeof initializeReadonlyTooltips === 'function') {
      initializeReadonlyTooltips();
    }
  }
}


//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//
// 編集画面
//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//

// 編集モード用：元の施術日を復元
function restoreOriginalDates() {
  if (window.recordsConfig.originalDates && window.recordsConfig.originalDates.length > 0) {
    window.recordsConfig.originalDates.forEach(date => {
      const dayElement = document.querySelector(`.calendar-day[data-date="${date}"]`);
      if (dayElement && !dayElement.classList.contains('closed-day')) {
        dayElement.classList.add('selected');
        window.selectedDates.add(date);
      }
    });

    // 往療距離の入力欄を生成
    updateHousecallDistanceInputs();

    // 往療距離の値を復元
    if (window.recordsConfig.originalDistances) {
      Object.keys(window.recordsConfig.originalDistances).forEach(date => {
        const distanceInput = document.querySelector(`input[name="housecall_distance[${date}]"]`);
        if (distanceInput) {
          distanceInput.value = window.recordsConfig.originalDistances[date] || 0;
        }
      });
    }

    // 施術実日数を更新
    updateTherapyDaysDisplay();
  }
}

// 編集画面の初期化
function initializeEditPage() {
  initializeCalendar(window.recordsConfig.initialYear, window.recordsConfig.initialMonth);
  setupCalendarEventListeners();
  setupFormEventListeners();
  restoreOriginalDates();
  updateConsentExpiryDisplay(); // 初期状態で同意有効期限を表示
  updateRecordFieldsState(); // 初期状態で実績フィールドの状態を更新
}


//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//
// 初期化処理
//━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━//

document.addEventListener('DOMContentLoaded', function() {
  // ページタイプを判定して適切な初期化を実行
  if (window.recordsConfig.originalDates !== undefined) {
    // 編集画面
    initializeEditPage();
  } else {
    // 新規登録画面
    initializeIndexPage();
  }
});
