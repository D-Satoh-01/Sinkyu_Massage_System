// public/js/schedules.js

// グローバル変数
let currentDate = new Date();
let viewMode = 'week'; // 'week' or 'month'
let scheduleData = [];
let selectedRecordId = null;

// 週ビューの仮想化設定
const WEEK_RENDER_BUFFER = 3; // 表示中週の前後に何週分描画するか
const WEEK_FETCH_PAST_BUFFER = 8; // データ取得時に現在週から遡る週数
const WEEK_FETCH_FUTURE_BUFFER = 12; // データ取得時に現在週から進む週数

// 週表示の全体範囲を計算（実績データの登録可能範囲と同期）
function calculateTotalWeeks() {
  // PHP側から渡された設定値を使用
  const startYear = window.scheduleConfig?.recordsStartYear || 2020;
  const startMonth = window.scheduleConfig?.recordsStartMonth || 1;
  const futureMonths = window.scheduleConfig?.futureMonths || 2;

  const startDate = new Date(startYear, startMonth - 1, 1); // 開始日
  const endDate = new Date();
  endDate.setMonth(endDate.getMonth() + futureMonths); // 現在から指定月数後

  const startWeek = getWeekStart(startDate);
  const endWeek = getWeekStart(endDate);

  const diffTime = endWeek - startWeek;
  const diffWeeks = Math.ceil(diffTime / (7 * 24 * 60 * 60 * 1000));

  return diffWeeks + 1; // 開始週も含める
}

// 表示開始週を取得
function getDisplayStartWeek() {
  const startYear = window.scheduleConfig?.recordsStartYear || 2020;
  const startMonth = window.scheduleConfig?.recordsStartMonth || 1;
  return getWeekStart(new Date(startYear, startMonth - 1, 1));
}

// 表示終了日を取得
function getDisplayEndDate() {
  const futureMonths = window.scheduleConfig?.futureMonths || 2;
  const endDate = new Date();
  endDate.setMonth(endDate.getMonth() + futureMonths);
  return endDate;
}

// 現在週を中心に描画する週範囲を計算
function getRenderRange(currentWeekIndex, dayColumnWidth) {
  const container = document.getElementById('schedule-container');
  const containerWidth = container ? container.offsetWidth : 1200;
  const weekWidth = dayColumnWidth * 7;
  const visibleWeeks = Math.max(1, Math.ceil((containerWidth - 40) / weekWidth));
  const windowWeeks = visibleWeeks + WEEK_RENDER_BUFFER * 2;

  let startWeekIndex = Math.max(0, currentWeekIndex - WEEK_RENDER_BUFFER);
  let endWeekIndex = Math.min(weekViewConfig.weeksToShow - 1, startWeekIndex + windowWeeks - 1);

  // 末尾で切り詰めた場合は開始位置を再調整
  startWeekIndex = Math.max(0, endWeekIndex - windowWeeks + 1);

  return { startWeekIndex, endWeekIndex };
}

let weekViewConfig = {
  weeksToShow: 0, // 初期化時に計算
  currentWeekOffset: 0, // 現在の週のオフセット
  isLoading: false,
  renderedWeeks: new Set(), // レンダリング済み週のインデックス
  displayStartWeek: null, // 表示開始週（Date）
  currentWeekStart: null, // 現在週開始日（Date）
  timeSlots: [], // 時間スロット配列
  renderStartWeekIndex: 0,
  renderEndWeekIndex: 0
};

// 初期化
document.addEventListener('DOMContentLoaded', function() {
  // 週数を計算
  weekViewConfig.weeksToShow = calculateTotalWeeks();

  initializeEventListeners();
  loadScheduleData();
  adjustScheduleContainerHeight();

  // ウィンドウリサイズ時に高さを再調整と週表示を再レンダリング
  let resizeTimeout;
  window.addEventListener('resize', function() {
    adjustScheduleContainerHeight();

    // リサイズ完了後に週表示を再レンダリング（スクロール位置を保持）
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      if (viewMode === 'week') {
        renderWeekView(true);
      }
    }, 300);
  });

  // スクロールイベントで表示中の週を検出し、必要に応じて追加レンダリング
  const container = document.getElementById('schedule-container');
  if (container) {
    let scrollTimeout;
    container.addEventListener('scroll', function() {
      if (viewMode !== 'week') return;

      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        updateVisibleWeekDisplay();
      }, 100);
    });
  }
});

// schedule-containerの高さを動的に調整
function adjustScheduleContainerHeight() {
  const container = document.getElementById('schedule-container');
  if (!container) return;

  // .main-contentを取得
  const mainContent = document.querySelector('.main-content');
  if (!mainContent) return;

  // containerFluidとcontainerの位置を取得
  const containerFluid = container.closest('.container-fluid');
  const mainContentRect = mainContent.getBoundingClientRect();
  const containerRect = container.getBoundingClientRect();

  // .main-contentの下端からcontainerの上端までの距離
  const spaceFromTop = containerRect.top - mainContentRect.top;

  // フッターの高さとマージンを取得
  const footer = mainContent.querySelector('footer');
  let footerTotalHeight = 0;
  if (footer) {
    const footerStyle = window.getComputedStyle(footer);
    footerTotalHeight = footer.offsetHeight + parseFloat(footerStyle.marginTop);
  }

  // .container-fluidのpadding-bottom
  const containerFluidStyle = window.getComputedStyle(containerFluid);
  const paddingBottom = parseFloat(containerFluidStyle.paddingBottom);

  // 利用可能な高さ = .main-contentの高さ - container上端までの距離 - padding-bottom - フッター高さ(マージン含む)
  const availableHeight = mainContentRect.height - spaceFromTop - paddingBottom - footerTotalHeight;

  container.style.maxHeight = `${availableHeight}px`;
}

// イベントリスナーの初期化
function initializeEventListeners() {
  // 施術者選択（選択変更時はページをリロードしてセッションに保存）
  document.getElementById('therapist-select').addEventListener('change', function() {
    const therapistId = this.value;
    window.location.href = window.location.pathname + '?therapist_id=' + therapistId;
  });

  // スクロールボタン
  document.getElementById('prev-btn').addEventListener('click', function() {
    navigateSchedule(-1);
  });

  document.getElementById('current-btn').addEventListener('click', function() {
    currentDate = new Date();
    loadScheduleData();
  });

  document.getElementById('next-btn').addEventListener('click', function() {
    navigateSchedule(1);
  });

  // 表示切り替えボタン
  document.getElementById('week-view-btn').addEventListener('click', function() {
    switchViewMode('week');
  });

  document.getElementById('month-view-btn').addEventListener('click', function() {
    switchViewMode('month');
  });

  // 編集ボタン
  document.getElementById('edit-record-btn').addEventListener('click', function() {
    if (selectedRecordId) {
      window.location.href = window.scheduleConfig.recordsIndexUrl + '?edit=' + selectedRecordId;
    }
  });

  // 登録画面へボタン
  document.getElementById('go-to-registration-btn').addEventListener('click', function() {
    window.location.href = window.scheduleConfig.recordsIndexUrl;
  });

  // モーダルを閉じる処理
  document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
    button.addEventListener('click', closeModal);
  });

  // モーダル外側クリックで閉じる
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
      closeModal();
    }
  });
}

// スケジュール遷移
function navigateSchedule(direction) {
  if (viewMode === 'week') {
    const container = document.getElementById('schedule-container');
    const preservedScrollLeft = container ? container.scrollLeft : 0;
    currentDate.setDate(currentDate.getDate() + (direction * 7));
    loadScheduleData(true, preservedScrollLeft);
  } else {
    currentDate.setMonth(currentDate.getMonth() + direction);
    loadScheduleData();
  }
}

// 表示モード切り替え
function switchViewMode(mode) {
  viewMode = mode;

  if (mode === 'week') {
    document.getElementById('week-view').style.display = 'block';
    document.getElementById('month-view').style.display = 'none';
    document.getElementById('week-view-btn').classList.add('btn-dark');
    document.getElementById('week-view-btn').classList.remove('btn-outline-dark');
    document.getElementById('month-view-btn').classList.remove('btn-dark');
    document.getElementById('month-view-btn').classList.add('btn-outline-dark');
  } else {
    document.getElementById('week-view').style.display = 'none';
    document.getElementById('month-view').style.display = 'block';
    document.getElementById('week-view-btn').classList.remove('btn-dark');
    document.getElementById('week-view-btn').classList.add('btn-outline-dark');
    document.getElementById('month-view-btn').classList.add('btn-dark');
    document.getElementById('month-view-btn').classList.remove('btn-outline-dark');
  }

  loadScheduleData();
}

// スケジュールデータ読み込み
function loadScheduleData(preserveScroll = false, preservedScrollLeft = 0) {
  const therapistId = document.getElementById('therapist-select').value;
  const dateRange = getDateRange();

  fetch(`${window.scheduleConfig.dataUrl}?therapist_id=${therapistId}&start_date=${dateRange.start}&end_date=${dateRange.end}`)
    .then(response => response.json())
    .then(data => {
      scheduleData = data;
      renderSchedule(preserveScroll, preservedScrollLeft);
      updateHeaderDisplay();
    })
    .catch(error => {
      console.error('スケジュールデータの読み込みエラー:', error);
    });
}

// 日付範囲を取得
function getDateRange() {
  let start, end;

  if (viewMode === 'week') {
    const displayStartWeek = getDisplayStartWeek();
    const currentWeekStart = getWeekStart(currentDate);

    const fetchStart = new Date(currentWeekStart);
    fetchStart.setDate(fetchStart.getDate() - WEEK_FETCH_PAST_BUFFER * 7);
    const clampedStart = fetchStart < displayStartWeek ? displayStartWeek : fetchStart;

    const fetchEnd = new Date(currentWeekStart);
    fetchEnd.setDate(fetchEnd.getDate() + WEEK_FETCH_FUTURE_BUFFER * 7 + 6);

    start = formatDate(clampedStart);
    end = formatDate(fetchEnd);
  } else {
    const monthStart = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    start = formatDate(monthStart);
    const monthEnd = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    end = formatDate(monthEnd);
  }

  return { start, end };
}

// 週の開始日（日曜日）を取得
function getWeekStart(date) {
  const d = new Date(date);
  const day = d.getDay();
  const diff = d.getDate() - day;
  return new Date(d.setDate(diff));
}

// 日付フォーマット（YYYY-MM-DD）
function formatDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

// ヘッダー表示を更新
function updateHeaderDisplay() {
  let year, startMonth, startDay, endMonth, endDay;

  if (viewMode === 'week') {
    // 週表示：スクロール位置に応じて表示（updateVisibleWeekDisplay()で更新されるのでここでは初期化のみ）
    const currentWeekStart = getWeekStart(currentDate);
    year = currentWeekStart.getFullYear();
    startMonth = currentWeekStart.getMonth() + 1;
    startDay = currentWeekStart.getDate();
    endMonth = currentWeekStart.getMonth() + 1;
    endDay = currentWeekStart.getDate() + 6;
  } else {
    // 月表示：月の1日〜最終日
    const monthStart = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const monthEnd = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

    year = monthStart.getFullYear();
    startMonth = monthStart.getMonth() + 1;
    startDay = monthStart.getDate();
    endMonth = monthEnd.getMonth() + 1;
    endDay = monthEnd.getDate();
  }

  document.getElementById('current-year').textContent = `${year}年`;
  document.getElementById('current-month-day').textContent = `${startMonth}月 ${startDay}日 ~ ${endMonth}月 ${endDay}日`;
}

// スケジュール表示
function renderSchedule(preserveScroll = false, preservedScrollLeft = 0) {
  if (viewMode === 'week') {
    renderWeekView(preserveScroll, preservedScrollLeft);
  } else {
    renderMonthView();
  }
}

// 週表示レンダリング（スクロールベース遅延読み込み）
function renderWeekView(preserveScrollPosition = false, preservedScrollLeft = 0) {
  const currentWeekStart = getWeekStart(currentDate);
  const displayStartWeek = getDisplayStartWeek();

  // スクロール位置を保存
  const container = document.getElementById('schedule-container');
  let savedScrollLeft = 0;
  if (preserveScrollPosition && container) {
    savedScrollLeft = preservedScrollLeft || container.scrollLeft;
  }

  // 各曜日セルの幅を計算
  const containerWidth = container ? container.offsetWidth : 1200;
  const timeColumnWidth = 40;
  const scrollbarWidth = 20;
  const availableWidth = containerWidth - timeColumnWidth - scrollbarWidth;
  const dayColumnWidth = Math.floor(availableWidth / 7);

  // 計算した幅をグローバル設定に保存
  weekViewConfig.dayColumnWidth = dayColumnWidth;
  weekViewConfig.displayStartWeek = displayStartWeek;
  weekViewConfig.currentWeekStart = currentWeekStart;

  // 現在週のインデックスを計算
  const diffTime = currentWeekStart - displayStartWeek;
  const currentWeekIndex = Math.floor(diffTime / (7 * 24 * 60 * 60 * 1000));

  // 描画対象の週範囲を計算
  const { startWeekIndex, endWeekIndex } = getRenderRange(currentWeekIndex, dayColumnWidth);
  weekViewConfig.renderStartWeekIndex = startWeekIndex;
  weekViewConfig.renderEndWeekIndex = endWeekIndex;

  // レンダリング済み週をクリア
  weekViewConfig.renderedWeeks.clear();

  // ヘッダーを必要範囲のみ生成
  renderWeekHeaders(displayStartWeek, currentWeekStart, dayColumnWidth, startWeekIndex, endWeekIndex);

  // ボディを初期化
  const tbody = document.getElementById('week-schedule-body');
  tbody.innerHTML = '';

  // timeSlotsを取得して保存
  const timeSlots = window.scheduleConfig.timeSlots || [];
  if (timeSlots.length === 0) {
    const startHour = parseInt(window.scheduleConfig.businessHoursStart.split(':')[0]);
    const endHour = parseInt(window.scheduleConfig.businessHoursEnd.split(':')[0]);
    for (let h = startHour; h <= endHour; h++) {
      timeSlots.push(`${String(h).padStart(2, '0')}:00`);
    }
  }
  weekViewConfig.timeSlots = timeSlots;

  // 描画対象範囲のみセルを生成
  renderWeekRangeWithCells(startWeekIndex, endWeekIndex, displayStartWeek, currentWeekStart, timeSlots, tbody);

  // スクロール位置をDOM反映後に設定
  requestAnimationFrame(() => {
    if (preserveScrollPosition && container) {
      container.scrollLeft = savedScrollLeft;
    } else {
      const headerRow = document.getElementById('week-header-row');
      if (headerRow && container) {
        const dayColumns = headerRow.querySelectorAll('th:not(:first-child)');
        if (dayColumns.length > 0) {
          const dayColumnWidth = dayColumns[0].offsetWidth;
          const weekWidth = dayColumnWidth * 7;
          const relativeWeekIndex = currentWeekIndex - startWeekIndex;
          const scrollPosition = weekWidth * relativeWeekIndex;
          container.scrollLeft = scrollPosition;
        }
      }
    }

    setTimeout(() => {
      updateVisibleWeekDisplay();
    }, 100);
  });
}

// ヘッダー行を生成
function renderWeekHeaders(displayStartWeek, currentWeekStart, dayColumnWidth, startWeekIndex, endWeekIndex) {
  const headerRow = document.getElementById('week-header-row');
  headerRow.innerHTML = `<th class="text-center align-middle p-0" style="width: 40px; min-width: 40px; max-width: 40px;">時刻</th>`;

  const dayNames = ['日', '月', '火', '水', '木', '金', '土'];

  for (let weekIndex = startWeekIndex; weekIndex <= endWeekIndex; weekIndex++) {
    const weekStart = new Date(displayStartWeek);
    weekStart.setDate(weekStart.getDate() + (weekIndex * 7));
    const weekNumber = getWeekNumber(weekStart);

    for (let i = 0; i < 7; i++) {
      const date = new Date(weekStart);
      date.setDate(date.getDate() + i);
      const th = document.createElement('th');
      th.style.width = `${dayColumnWidth}px`;
      th.style.minWidth = `${dayColumnWidth}px`;
      th.style.maxWidth = `${dayColumnWidth}px`;
      th.className = 'text-center';

      // 現在の週の場合は背景色を変更
      const isCurrentWeek = weekStart.getTime() === currentWeekStart.getTime();
      if (isCurrentWeek) {
        th.style.backgroundColor = '#e3f2fd';
      }

      // 週番号は各週の最初の日（日曜日）のみ表示
      if (i === 0) {
        th.innerHTML = `<div class="fw-bold" style="font-size: 0.75rem.">第${weekNumber}週</div><div>（${dayNames[i]}）${date.getMonth() + 1}/${date.getDate()}</div>`;
      } else {
        th.innerHTML = `<div>（${dayNames[i]}）${date.getMonth() + 1}/${date.getDate()}</div>`;
      }

      headerRow.appendChild(th);
    }
  }
}

// 指定範囲の週のセルを生成してイベントをレンダリング
function renderWeekRangeWithCells(startWeekIndex, endWeekIndex, displayStartWeek, currentWeekStart, timeSlots, tbody) {
  const dayColumnWidth = weekViewConfig.dayColumnWidth || 150;
  const baseWeekIndex = weekViewConfig.renderStartWeekIndex || 0;

  // tbodyの行が存在しない場合は作成
  if (tbody.children.length === 0) {
    // 初回：全ての時間行を作成
    timeSlots.forEach((timeSlot) => {
      const [hour] = timeSlot.split(':').map(Number);

      // 1時間おきの主線 (00分)
      const tr = createTimeRow(hour, 0, true);
      tbody.appendChild(tr);

      // 10分おきの破線
      for (let min = 10; min < 60; min += 10) {
        const subTr = createTimeRow(hour, min, false);
        tbody.appendChild(subTr);
      }
    });
  }

  // 各行のセルを一括作成
  const rows = Array.from(tbody.querySelectorAll('tr'));
  
  for (let weekIndex = startWeekIndex; weekIndex <= endWeekIndex; weekIndex++) {
    // すでにレンダリング済みならスキップ
    if (weekViewConfig.renderedWeeks.has(weekIndex)) continue;

    const weekStart = new Date(displayStartWeek);
    weekStart.setDate(weekStart.getDate() + (weekIndex * 7));
    const isCurrentWeek = weekStart.getTime() === currentWeekStart.getTime();

    // 週ごとに全行のセルをバッチ作成
    rows.forEach((row, rowIndex) => {
      const hour = parseInt(row.dataset.hour);
      const minute = parseInt(row.dataset.minute);
      const fragment = document.createDocumentFragment();

      // 該当週の7日分のセルを作成
      for (let dayIndex = 0; dayIndex < 7; dayIndex++) {
        const date = new Date(weekStart);
        date.setDate(date.getDate() + dayIndex);

        const td = document.createElement('td');
        td.className = 'position-relative';
        td.style.cssText = `width: ${dayColumnWidth}px; min-width: ${dayColumnWidth}px; max-width: ${dayColumnWidth}px; padding: 0; vertical-align: top; overflow: visible;`;
        td.dataset.date = formatDate(date);
        td.dataset.hour = hour;
        td.dataset.minute = minute;

        if (isCurrentWeek) {
          td.style.backgroundColor = '#f0f8ff';
        }

        // イベントを追加（00分の行のみ）
        if (minute === 0) {
          const events = getEventsForDateTime(date, hour, minute);
          events.forEach(event => {
            const eventDiv = createEventElement(event);
            td.appendChild(eventDiv);
          });
        }

        // クリックイベント
        td.addEventListener('click', function(e) {
          if (e.target.classList.contains('schedule-event') || e.target.closest('.schedule-event')) {
            const eventElement = e.target.classList.contains('schedule-event') ? e.target : e.target.closest('.schedule-event');
            showEventDetail(parseInt(eventElement.dataset.recordId));
          } else {
            showNewEventModal(date, hour, minute);
          }
        });

        fragment.appendChild(td);
      }

      // フラグメントを一括挿入
      const relativeWeekIndex = weekIndex - baseWeekIndex;
      const insertIndex = 1 + (relativeWeekIndex * 7);
      if (row.cells.length <= insertIndex) {
        row.appendChild(fragment);
      } else {
        row.insertBefore(fragment, row.cells[insertIndex]);
      }
    });

    weekViewConfig.renderedWeeks.add(weekIndex);
  }
}

// 時間行を作成（セルなし）
function createTimeRow(hour, minute, isMainLine) {
  const tr = document.createElement('tr');
  tr.style.height = '20px';
  tr.style.borderTop = isMainLine ? '2px solid #ccc' : '1px dashed #ccc';
  tr.dataset.hour = hour;
  tr.dataset.minute = minute;

  // 時刻セルのみ
  const timeTd = document.createElement('td');
  timeTd.className = 'text-center';
  timeTd.style.width = '40px';
  timeTd.style.minWidth = '40px';
  timeTd.style.maxWidth = '40px';

  if (minute === 0 || minute === 30) {
    timeTd.textContent = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
    timeTd.className = 'text-center fw-semibold p-0';
    timeTd.style.fontSize = '11px';
  }

  tr.appendChild(timeTd);

  return tr;
}



// 現在の週にスクロール
function scrollToCurrentWeek() {
  const container = document.getElementById('schedule-container');
  if (!container) return;

  const headerRow = document.getElementById('week-header-row');
  if (!headerRow) return;

  // 1週間分の幅（7日 × 各列の幅）
  const dayColumns = headerRow.querySelectorAll('th:not(:first-child)');
  if (dayColumns.length === 0) return;

  const dayColumnWidth = dayColumns[0].offsetWidth;
  const weekWidth = dayColumnWidth * 7;

  // 表示開始週から現在週までの週数を計算
  const displayStartWeek = getDisplayStartWeek();
  const currentWeekStart = getWeekStart(currentDate);
  const diffTime = currentWeekStart - displayStartWeek;
  const currentWeekIndex = Math.floor(diffTime / (7 * 24 * 60 * 60 * 1000));
  const renderStartWeekIndex = weekViewConfig.renderStartWeekIndex || 0;
  const relativeWeekIndex = Math.max(0, currentWeekIndex - renderStartWeekIndex);

  // 現在週の開始位置にスクロール
  const scrollPosition = weekWidth * relativeWeekIndex;

  container.scrollLeft = scrollPosition;
}

// 表示中の週の情報を更新
function updateVisibleWeekDisplay() {
  const container = document.getElementById('schedule-container');
  if (!container) return;

  const headerRow = document.getElementById('week-header-row');
  if (!headerRow) return;

  const dayColumns = headerRow.querySelectorAll('th:not(:first-child)');
  if (dayColumns.length === 0) return;

  const dayColumnWidth = dayColumns[0].offsetWidth;
  const scrollLeft = container.scrollLeft;
  const containerWidth = container.offsetWidth;
  const timeColumnWidth = 40;

  // 実際に表示されている範囲を計算
  // 左端に70%以上表示されている日のインデックス（時刻列を除く）
  const leftCellStart = Math.floor(scrollLeft / dayColumnWidth) * dayColumnWidth;
  const leftVisibleIndex = scrollLeft > leftCellStart + (dayColumnWidth * 0.3)
    ? Math.ceil(scrollLeft / dayColumnWidth)
    : Math.floor(scrollLeft / dayColumnWidth);

  // 右端に70%以上表示されている日のインデックス
  const rightScrollEdge = scrollLeft + containerWidth - timeColumnWidth;
  const rightCellEnd = Math.ceil(rightScrollEdge / dayColumnWidth) * dayColumnWidth;
  const rightVisibleIndex = rightScrollEdge < rightCellEnd - (dayColumnWidth * 0.3)
    ? Math.floor(rightScrollEdge / dayColumnWidth) - 1
    : Math.ceil(rightScrollEdge / dayColumnWidth) - 1;

  // 表示中の7日分の開始・終了インデックスを決定
  // （完全に見える日のみを選択）
  let startDayIndex, endDayIndex;

  const visibleDayCount = rightVisibleIndex - leftVisibleIndex + 1;

  if (visibleDayCount >= 7) {
    // 7日以上完全に表示されている場合、左端から7日分
    startDayIndex = leftVisibleIndex;
    endDayIndex = leftVisibleIndex + 6;
  } else {
    // 7日未満の場合は表示されている範囲をそのまま使用
    startDayIndex = leftVisibleIndex;
    endDayIndex = rightVisibleIndex;
  }

  // 表示開始週の日曜日を基準日として取得（実績データ登録可能範囲の開始週から）
  const displayStartWeek = getDisplayStartWeek();
  const baseWeekIndex = weekViewConfig.renderStartWeekIndex || 0;

  // 表示中の開始日と終了日を計算
  const visibleStart = new Date(displayStartWeek);
  visibleStart.setDate(visibleStart.getDate() + (baseWeekIndex * 7) + startDayIndex);

  const visibleEnd = new Date(displayStartWeek);
  visibleEnd.setDate(visibleEnd.getDate() + (baseWeekIndex * 7) + endDayIndex);

  // 年月日テキストを更新
  const year = visibleStart.getFullYear();
  const startMonth = visibleStart.getMonth() + 1;
  const startDay = visibleStart.getDate();
  const endMonth = visibleEnd.getMonth() + 1;
  const endDay = visibleEnd.getDate();

  document.getElementById('current-year').textContent = `${year}年`;
  document.getElementById('current-month-day').textContent = `${startMonth}月 ${startDay}日 ~ ${endMonth}月 ${endDay}日`;
}

// 指定日時のイベントを取得
function getEventsForDateTime(date, hour, minute) {
  const dateStr = formatDate(date);

  return scheduleData.filter(event => {
    if (event.date !== dateStr) return false;

    const startTime = event.start_time;
    const startHour = parseInt(startTime.split(':')[0]);
    const startMin = parseInt(startTime.split(':')[1]);

    return startHour === hour && startMin === minute;
  });
}

// イベント要素を作成
function createEventElement(event) {
  const div = document.createElement('div');
  div.className = 'schedule-event';

  // 施術時間の長さを計算（分単位）
  const startParts = event.start_time.split(':');
  const endParts = event.end_time.split(':');
  const startMinutes = parseInt(startParts[0]) * 60 + parseInt(startParts[1]);
  const endMinutes = parseInt(endParts[0]) * 60 + parseInt(endParts[1]);
  const duration = endMinutes - startMinutes;

  // 高さを計算（10分 = 20px）
  const height = (duration / 10) * 20;

  div.style.cssText = `
    position: absolute;
    top: 0;
    left: 2px;
    right: 2px;
    height: ${height}px;
    background-color: #007bff;
    color: white;
    padding: 2px 4px;
    border-radius: 4px;
    font-size: 0.8rem;
    cursor: pointer;
    overflow: hidden;
    z-index: 5;
  `;
  div.dataset.recordId = event.id;

  const startTime = event.start_time.substring(0, 5);
  const endTime = event.end_time.substring(0, 5);
  div.innerHTML = `<div class="fw-medium">${startTime} ~ ${endTime}</div><div class="fw-medium">${event.user_name}</div>`;

  return div;
}

// イベント詳細モーダルを表示
function showEventDetail(recordId) {
  const event = scheduleData.find(e => e.id === recordId);
  if (!event) return;

  selectedRecordId = recordId;

  document.getElementById('detail-user-name').textContent = event.user_name;
  document.getElementById('detail-start-datetime').textContent = `${event.date} ${event.start_time}`;
  document.getElementById('detail-end-datetime').textContent = `${event.date} ${event.end_time}`;
  document.getElementById('detail-therapy-type').textContent = event.therapy_type || '未設定';

  const modalElement = document.getElementById('event-detail-modal');
  if (modalElement.parentElement !== document.body) {
    document.body.appendChild(modalElement);
  }
  const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
  modalInstance.show();
}

// 新規イベントモーダルを表示
function showNewEventModal(date, hour, minute) {
  const dateStr = formatDate(date);
  const timeStr = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;

  document.getElementById('new-start-datetime').textContent = `${dateStr} ${timeStr}`;

  const modalElement = document.getElementById('new-event-modal');
  if (modalElement.parentElement !== document.body) {
    document.body.appendChild(modalElement);
  }
  const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
  modalInstance.show();
}

// モーダルを閉じる
function closeModal() {
  document.querySelectorAll('.modal').forEach(modal => {
    const instance = bootstrap.Modal.getInstance(modal);
    if (instance) {
      instance.hide();
    } else {
      modal.classList.remove('show');
      modal.style.display = 'none';
    }
  });
}

// 週番号を取得（年内の第N週）
function getWeekNumber(date) {
  const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
  const dayNum = d.getUTCDay() || 7;
  d.setUTCDate(d.getUTCDate() + 4 - dayNum);
  const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
  return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
}

// 月表示レンダリング
function renderMonthView() {
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);

  const tbody = document.getElementById('month-schedule-body');
  tbody.innerHTML = '';

  // カレンダーの開始日（月の最初の日が含まれる週の日曜日）
  const calendarStart = getWeekStart(firstDay);

  let currentCalendarDate = new Date(calendarStart);

  // 週ごとに行を生成
  while (currentCalendarDate <= lastDay || currentCalendarDate.getDay() !== 0) {
    const tr = document.createElement('tr');

    // 週番号
    const weekTd = document.createElement('td');
    weekTd.textContent = getWeekNumber(currentCalendarDate);
    weekTd.className = 'text-center fw-bold';
    tr.appendChild(weekTd);

    // 日〜土のセル
    for (let i = 0; i < 7; i++) {
      const td = document.createElement('td');
      td.className = 'position-relative';
      td.style.cssText = 'height: 100px; min-height: 100px; max-height: 100px; vertical-align: top; padding: 2px; overflow: hidden;';

      const dateDiv = document.createElement('div');
      dateDiv.className = 'fw-bold';
      dateDiv.style.cssText = 'font-size: 12px; margin-bottom: 2px;';
      dateDiv.textContent = currentCalendarDate.getDate();

      // 当月以外はグレー表示
      if (currentCalendarDate.getMonth() !== month) {
        td.style.backgroundColor = '#f0f0f0';
        dateDiv.style.color = '#999';
      }

      td.appendChild(dateDiv);

      // その日のイベントを表示
      const dayEvents = getDayEvents(currentCalendarDate);
      dayEvents.forEach(event => {
        const eventDiv = createMonthEventElement(event);
        td.appendChild(eventDiv);
      });

      // クリックイベント
      const clickDate = new Date(currentCalendarDate);
      td.addEventListener('click', function(e) {
        if (e.target.classList.contains('schedule-event') || e.target.closest('.schedule-event')) {
          const eventElement = e.target.classList.contains('schedule-event') ? e.target : e.target.closest('.schedule-event');
          showEventDetail(parseInt(eventElement.dataset.recordId));
        } else {
          showNewEventModal(clickDate, 9, 0);
        }
      });

      tr.appendChild(td);
      currentCalendarDate.setDate(currentCalendarDate.getDate() + 1);
    }

    tbody.appendChild(tr);

    if (currentCalendarDate > lastDay && currentCalendarDate.getDay() === 0) {
      break;
    }
  }
}

// 指定日のイベントを取得
function getDayEvents(date) {
  const dateStr = formatDate(date);
  return scheduleData.filter(event => event.date === dateStr);
}

// 月表示用イベント要素を作成
function createMonthEventElement(event) {
  const div = document.createElement('div');
  div.className = 'schedule-event text-truncate';
  div.style.cssText = 'background-color: #007bff; color: white; padding: 1px 3px; border-radius: 2px; margin-bottom: 1px; font-size: 0.8rem; cursor: pointer; line-height: 1.2;';
  div.dataset.recordId = event.id;

  const startTime = event.start_time.substring(0, 5);
  div.textContent = `${startTime}｜${event.user_name}`;

  return div;
}

// スクロール位置に応じて未レンダリングの週を追加読み込み
function loadWeeksNearViewport() {
  // 仮想化後は常に描画済みの範囲のみを表示するため、追加ロードは不要
  return;
}
