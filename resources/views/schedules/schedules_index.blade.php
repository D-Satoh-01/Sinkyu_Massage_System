<!-- resources/views/schedules/schedules_index.blade.php -->


<x-app-layout>
  <x-page-header :title="$page_header_title" />

  <div class="container-fluid p-3">
    <!-- 施術者セレクトボックス -->
    <div class="row mb-3">
      <div class="col-md-4">
        <label for="therapist-select" class="form-label fw-bold">施術者</label>
        <select id="therapist-select">
          <option value="">╌╌╌</option>
          @foreach($therapists as $therapist)
            <option value="{{ $therapist->id }}" {{ $selectedTherapistId == $therapist->id ? 'selected' : '' }}>
              {{ $therapist->therapist_name }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <!-- スケジュール表コントロール -->
    <div class="row mb-2">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
          <!-- 左：スクロールボタン -->
          <div class="btn-group" role="group">
            <button type="button" id="prev-btn" class="btn btn-outline-primary">◀</button>
            <button type="button" id="current-btn" class="btn btn-outline-primary fw-semibold">［現在］</button>
            <button type="button" id="next-btn" class="btn btn-outline-primary">▶</button>
          </div>

          <!-- 中央：表示中の年月日 -->
          <div class="text-center">
            <div id="current-year" class="fs-5 fw-bold"></div>
            <div id="current-month-day" class="fs-6"></div>
          </div>

          <!-- 右：表示切り替えボタン -->
          <div class="btn-group" role="group">
            <button type="button" id="week-view-btn" class="btn btn-primary fw-semibold">週表示</button>
            <button type="button" id="month-view-btn" class="btn btn-outline-primary fw-semibold">月表示</button>
          </div>
        </div>
      </div>
    </div>

    
    <!-- スケジュール表 -->
    <div class="row">
      <div class="col-12">
        <div id="schedule-container" class="border rounded bg-white" style="overflow: auto; max-height: calc(100vh - 250px); position: relative; z-index: 1;">
          <!-- 週表示 -->
          <div id="week-view" style="display: block;">
            <table class="table table-bordered mb-0" id="week-schedule-table">
              <thead class="table-light sticky-top">
                <tr id="week-header-row" style="font-size: 0.8rem">
                  <!-- テーブルヘッダーがJavaScriptで生成される -->
                </tr>
              </thead>
              <tbody id="week-schedule-body">
                <!-- 時間帯ごとの行がJavaScriptで生成される -->
              </tbody>
            </table>
          </div>

          <!-- 月表示 -->
          <div id="month-view" style="display: none;">
            <table class="table table-bordered mb-0" id="month-schedule-table" style="table-layout: fixed;">
              <thead class="table-light sticky-top">
                <tr>
                  <th style="width: 50px;">週</th>
                  <th>日</th>
                  <th>月</th>
                  <th>火</th>
                  <th>水</th>
                  <th>木</th>
                  <th>金</th>
                  <th>土</th>
                </tr>
              </thead>
              <tbody id="month-schedule-body">
                <!-- 月のカレンダーがJavaScriptで生成される -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 施術詳細モーダル -->
  <div class="modal fade" id="event-detail-modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">施術詳細</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <strong>利用者氏名：</strong>
            <span id="detail-user-name"></span>
          </div>
          <div class="mb-2">
            <strong>開始日時：</strong>
            <span id="detail-start-datetime"></span>
          </div>
          <div class="mb-2">
            <strong>終了日時：</strong>
            <span id="detail-end-datetime"></span>
          </div>
          <div class="mb-2">
            <strong>施術内容：</strong>
            <span id="detail-therapy-type"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
          <button type="button" class="btn btn-primary" id="edit-record-btn">編集</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 新規登録モーダル -->
  <div class="modal fade" id="new-event-modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">新規登録</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <strong>開始日時：</strong>
            <span id="new-start-datetime"></span>
          </div>
          <div class="mb-2">
            <strong>利用者氏名：</strong>
            <input type="text" class="form-control" id="new-user-name" readonly placeholder="未選択">
          </div>
          <div class="mb-2">
            <strong>施術者：</strong>
            <input type="text" class="form-control" id="new-therapist-name" readonly placeholder="未選択">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
          <button type="button" class="btn btn-primary" id="go-to-registration-btn">登録画面へ</button>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      // PHP変数をJavaScriptに渡す
      window.scheduleConfig = {
        therapistId: '{{ $selectedTherapistId ?? "" }}',
        businessHoursStart: '{{ $businessHoursStart }}',
        businessHoursEnd: '{{ $businessHoursEnd }}',
        dataUrl: '{{ route("schedules.data") }}',
        recordsIndexUrl: '{{ route("records.index") }}'
      };
    </script>
    <script src="{{ asset('js/schedules.js') }}"></script>
  @endpush
</x-app-layout>
