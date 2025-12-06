// public/js/schedules.js

// グローバル変数
let currentDate = new Date();
let viewMode = 'week'; // 'week' or 'month'
let scheduleData = [];
let selectedRecordId = null;

// 初期化
document.addEventListener('DOMContentLoaded', function() {
  initializeEventListeners();
  loadScheduleData();
  adjustScheduleContainerHeight();

  // ウィンドウリサイズ時に高さを再調整
  window.addEventListener('resize', adjustScheduleContainerHeight);
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
    currentDate.setDate(currentDate.getDate() + (direction * 7));
  } else {
    currentDate.setMonth(currentDate.getMonth() + direction);
  }
  loadScheduleData();
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
function loadScheduleData() {
  const therapistId = document.getElementById('therapist-select').value;
  const dateRange = getDateRange();

  fetch(`${window.scheduleConfig.dataUrl}?therapist_id=${therapistId}&start_date=${dateRange.start}&end_date=${dateRange.end}`)
    .then(response => response.json())
    .then(data => {
      scheduleData = data;
      renderSchedule();
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
    const weekStart = getWeekStart(currentDate);
    start = formatDate(weekStart);
    const weekEnd = new Date(weekStart);
    weekEnd.setDate(weekEnd.getDate() + 6);
    end = formatDate(weekEnd);
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
    // 週表示：週の開始日〜終了日
    const weekStart = getWeekStart(currentDate);
    const weekEnd = new Date(weekStart);
    weekEnd.setDate(weekEnd.getDate() + 6);

    year = weekStart.getFullYear();
    startMonth = weekStart.getMonth() + 1;
    startDay = weekStart.getDate();
    endMonth = weekEnd.getMonth() + 1;
    endDay = weekEnd.getDate();
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
function renderSchedule() {
  if (viewMode === 'week') {
    renderWeekView();
  } else {
    renderMonthView();
  }
}

// 週表示レンダリング
function renderWeekView() {
  const weekStart = getWeekStart(currentDate);
  const weekNumber = getWeekNumber(weekStart);

  // ヘッダー行を生成
  const headerRow = document.getElementById('week-header-row');
  headerRow.innerHTML = `<th class="text-center align-middle p-0" style="width: 60px;">第${weekNumber}週</th>`;

  const dayNames = ['日', '月', '火', '水', '木', '金', '土'];
  for (let i = 0; i < 7; i++) {
    const date = new Date(weekStart);
    date.setDate(date.getDate() + i);
    const th = document.createElement('th');
    th.innerHTML = `<div>（${dayNames[i]}）${date.getMonth() + 1}/${date.getDate()}</div>`;
    headerRow.appendChild(th);
  }

  // 時間帯ごとの行を生成
  const tbody = document.getElementById('week-schedule-body');
  tbody.innerHTML = '';

  // timeSlotsがない場合はデフォルト値を使用
  const timeSlots = window.scheduleConfig.timeSlots || [];
  if (timeSlots.length === 0) {
    const startHour = parseInt(window.scheduleConfig.businessHoursStart.split(':')[0]);
    const endHour = parseInt(window.scheduleConfig.businessHoursEnd.split(':')[0]);
    for (let h = startHour; h <= endHour; h++) {
      timeSlots.push(`${String(h).padStart(2, '0')}:00`);
    }
  }

  timeSlots.forEach((timeSlot) => {
    const [hour] = timeSlot.split(':').map(Number);

    // 1時間おきの主線 (00分)
    const tr = createWeekTimeRow(hour, 0, weekStart, true, weekNumber);
    tbody.appendChild(tr);

    // 10分おきの破線
    for (let min = 10; min < 60; min += 10) {
      const subTr = createWeekTimeRow(hour, min, weekStart, false, weekNumber);
      tbody.appendChild(subTr);
    }
  });
}

// 週表示の時間行を作成
function createWeekTimeRow(hour, minute, weekStart, isMainLine, weekNumber) {
  const tr = document.createElement('tr');
  tr.style.height = '20px';
  tr.style.borderTop = isMainLine ? '2px solid #ccc' : '1px dashed #ccc';

  // 時刻セル（30分刻みで表示）
  const timeTd = document.createElement('td');
  timeTd.className = 'text-center';

  // 00分または30分の場合のみ時刻を表示
  if (minute === 0 || minute === 30) {
    timeTd.textContent = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
    timeTd.className = 'text-center fw-semibold p-0';
    timeTd.style.fontSize = '11px';
  }

  tr.appendChild(timeTd);

  // 各曜日のセル
  for (let i = 0; i < 7; i++) {
    const date = new Date(weekStart);
    date.setDate(date.getDate() + i);
    const td = document.createElement('td');
    td.className = 'position-relative';
    td.style.cssText = 'padding: 0; vertical-align: top; overflow: visible;';
    td.dataset.date = formatDate(date);
    td.dataset.hour = hour;
    td.dataset.minute = minute;

    // 施術時間帯の表示（各イベントの開始時刻の行にのみ追加）
    if (minute === 0) {
      const events = getEventsForDateTime(date, hour, minute);
      events.forEach(event => {
        const eventDiv = createEventElement(event);
        td.appendChild(eventDiv);
      });
    }

    // クリックイベント
    td.addEventListener('click', function(e) {
      console.log('Cell clicked!', e.target);
      if (e.target.classList.contains('schedule-event') || e.target.closest('.schedule-event')) {
        const eventElement = e.target.classList.contains('schedule-event') ? e.target : e.target.closest('.schedule-event');
        console.log('Schedule event clicked, recordId:', eventElement.dataset.recordId);
        showEventDetail(parseInt(eventElement.dataset.recordId));
      } else {
        console.log('Empty cell clicked, showing new event modal');
        showNewEventModal(date, hour, minute);
      }
    });

    tr.appendChild(td);
  }

  return tr;
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
    z-index: 10;
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
