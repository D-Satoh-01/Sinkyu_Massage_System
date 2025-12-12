<!-- resources/views/deposits/deposits_index.blade.php -->

<x-app-layout>
  <x-page-header
    :title="$page_header_title"
    :breadcrumbs="App\Support\Breadcrumbs::generate('deposits.index')"
  />

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

  <!-- 入金データ一覧表示エリア -->
  <div id="deposits-list-area" style="max-height: 75vh; overflow-y: auto; overflow-x: hidden; border: 1px solid #dee2e6; padding: 1rem;">
    @foreach($depositsByYear as $year => $yearData)
      @php
        $hasDeposits = $yearData['has_deposits'];
        $months = $yearData['months'];
        $collapseId = 'year-' . $year;
      @endphp

      <!-- 年ヘッダー（折り畳み・展開ボタン） -->
      <div class="year-header mb-2">
        <button
          class="btn btn-link text-decoration-none p-0 fw-bold fs-4 text-dark d-flex align-items-center"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#{{ $collapseId }}"
          aria-expanded="false"
          aria-controls="{{ $collapseId }}"
        >
          <span class="toggle-icon">▸</span>
          <span>［ {{ $year }} ］</span>
        </button>
      </div>

      <!-- 月別データ（折り畳み可能） -->
      <div class="collapse" id="{{ $collapseId }}" data-year="{{ $year }}">
        @foreach($months as $item)
          @php
            $yearMonth = $item['year_month'];
          @endphp
          <div class="deposit-month-section mb-4 ms-4" data-year-month="{{ $yearMonth }}">
            <div class="fw-bold fs-5">{{ $item['year'] }}年 {{ sprintf('%02d', $item['month']) }}月</div>
            <div class="deposit-data-container" data-year-month="{{ $yearMonth }}">
              <!-- データはAjaxで動的に読み込まれる -->
              <div class="text-center py-3">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                読み込み中...
              </div>
            </div>
          </div>
          <hr>
        @endforeach
      </div>
    @endforeach
  </div>

  @push('scripts')
  <script>
    // PHP変数をJavaScriptに渡す
    window.depositsConfig = {
      scrollToYearMonth: @json($scrollToYearMonth),
      getMonthDataUrl: '{{ route("deposits.getMonthData", ":yearMonth") }}',
      updateUrl: '{{ route("deposits.update", ":id") }}',
      csrfToken: '{{ csrf_token() }}'
    };

    // 展開中の月を追跡
    let currentExpandedMonth = null;

    // 年ヘッダーのアイコン切り替え
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.collapse').forEach(function(collapseElement) {
        collapseElement.addEventListener('show.bs.collapse', function() {
          const button = document.querySelector(`[data-bs-target="#${this.id}"]`);
          if (button) {
            const icon = button.querySelector('.toggle-icon');
            if (icon) icon.textContent = '▾';
          }
        });

        collapseElement.addEventListener('hide.bs.collapse', function() {
          const button = document.querySelector(`[data-bs-target="#${this.id}"]`);
          if (button) {
            const icon = button.querySelector('.toggle-icon');
            if (icon) icon.textContent = '▸';
          }
        });
      });

      // 現在年月を自動展開
      if (window.depositsConfig.scrollToYearMonth) {
        const targetSection = document.querySelector(`[data-year-month="${window.depositsConfig.scrollToYearMonth}"]`);
        if (targetSection) {
          const collapseParent = targetSection.closest('.collapse');
          if (collapseParent) {
            const collapseInstance = new bootstrap.Collapse(collapseParent, { toggle: true });

            setTimeout(() => {
              expandMonth(window.depositsConfig.scrollToYearMonth);
              scrollToMonth(window.depositsConfig.scrollToYearMonth);
            }, 350);
          }
        }
      }
    });

    // 月データを展開
    function expandMonth(yearMonth) {
      // 他の展開中の月を格納
      if (currentExpandedMonth && currentExpandedMonth !== yearMonth) {
        const prevContainer = document.querySelector(`[data-year-month="${currentExpandedMonth}"] .deposit-data-container`);
        if (prevContainer) {
          prevContainer.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 読み込み中...</div>';
        }

        // 他の展開中の年も格納
        const prevSection = document.querySelector(`[data-year-month="${currentExpandedMonth}"]`);
        if (prevSection) {
          const prevCollapseParent = prevSection.closest('.collapse');
          if (prevCollapseParent && prevCollapseParent.classList.contains('show')) {
            const prevYear = prevCollapseParent.getAttribute('data-year');
            const currentYear = yearMonth.split('-')[0];
            if (prevYear !== currentYear) {
              const collapseInstance = bootstrap.Collapse.getInstance(prevCollapseParent);
              if (collapseInstance) {
                collapseInstance.hide();
              } else {
                new bootstrap.Collapse(prevCollapseParent, { toggle: false }).hide();
              }
            }
          }
        }
      }

      currentExpandedMonth = yearMonth;

      // データを取得して表示
      const container = document.querySelector(`[data-year-month="${yearMonth}"] .deposit-data-container`);
      if (!container) return;

      const url = window.depositsConfig.getMonthDataUrl.replace(':yearMonth', yearMonth);

      fetch(url)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            if (data.deposits.length === 0) {
              container.innerHTML = '<span class="text-secondary">該当データなし</span>';
            } else {
              container.innerHTML = renderDepositsTable(data.deposits);
            }
          } else {
            container.innerHTML = '<span class="text-danger">データの取得に失敗しました</span>';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          container.innerHTML = '<span class="text-danger">データの取得に失敗しました</span>';
        });
    }

    // 入金データテーブルをレンダリング
    function renderDepositsTable(deposits) {
      let html = '<div style="overflow-x: auto;"><table class="table table-bordered" style="font-size: 0.85rem; white-space: nowrap;"><thead class="table-light"><tr>';
      html += '<th class="text-center align-middle">ID</th>';
      html += '<th class="text-center align-middle">保険者</th>';
      html += '<th class="text-center align-middle">被保険者</th>';
      html += '<th class="text-center align-middle">受療者</th>';
      html += '<th class="text-center align-middle">治療日</th>';
      html += '<th class="text-center align-middle">施術種類</th>';
      html += '<th class="text-center align-middle">療養費合計</th>';
      html += '<th class="text-center align-middle">自己負担額</th>';
      html += '<th class="text-center align-middle">保険請求額</th>';
      html += '<th class="text-center align-middle">入金額</th>';
      html += '<th class="text-center align-middle">入金日</th>';
      html += '<th class="text-center align-middle">登録</th>';
      html += '</tr></thead><tbody>';

      deposits.forEach(deposit => {
        html += '<tr>';
        html += `<td class="text-center align-middle">${deposit.id}</td>`;
        html += `<td class="align-middle">${deposit.insurer_name}</td>`;
        html += `<td class="align-middle">${deposit.insured_name}</td>`;
        html += `<td class="align-middle">${deposit.clinic_user_name}</td>`;
        html += `<td class="align-middle" style="white-space: pre-line;">${deposit.treatment_dates}</td>`;
        html += `<td class="text-center align-middle">${deposit.treatment_type}</td>`;
        html += `<td class="align-middle"><input type="number" class="form-control form-control-sm" data-id="${deposit.id}" data-field="total_amount" value="${deposit.total_amount}" min="0" style="width: 100px;"></td>`;
        html += `<td class="align-middle"><input type="number" class="form-control form-control-sm" data-id="${deposit.id}" data-field="selfpay_amount" value="${deposit.selfpay_amount}" min="0" style="width: 100px;"></td>`;
        html += `<td class="align-middle"><input type="number" class="form-control form-control-sm" data-id="${deposit.id}" data-field="insurance_billing_amount" value="${deposit.insurance_billing_amount}" min="0" style="width: 100px;"></td>`;
        html += `<td class="align-middle"><input type="number" class="form-control form-control-sm" data-id="${deposit.id}" data-field="deposit_amount" value="${deposit.deposit_amount}" min="0" style="width: 100px;"></td>`;
        html += `<td class="align-middle"><input type="date" class="form-control form-control-sm" data-id="${deposit.id}" data-field="deposit_date" value="${deposit.deposit_date}" style="width: 150px;"></td>`;
        html += `<td class="text-center align-middle"><button type="button" class="btn btn-sm btn-primary" onclick="saveDeposit(${deposit.id})">登録</button></td>`;
        html += '</tr>';
      });

      html += '</tbody></table></div>';
      return html;
    }

    // 入金データを保存
    function saveDeposit(depositId) {
      const inputs = document.querySelectorAll(`input[data-id="${depositId}"]`);
      const data = { _token: window.depositsConfig.csrfToken };

      inputs.forEach(input => {
        const field = input.getAttribute('data-field');
        data[field] = input.value || (input.type === 'date' ? null : 0);
      });

      const url = window.depositsConfig.updateUrl.replace(':id', depositId);

      fetch(url, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': window.depositsConfig.csrfToken
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
        } else {
          alert('エラー: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('データの保存に失敗しました');
      });
    }

    // 指定年月へスクロール
    function scrollToMonth(yearMonth) {
      const targetSection = document.querySelector(`[data-year-month="${yearMonth}"]`);
      if (targetSection) {
        const container = document.getElementById('deposits-list-area');
        if (container) {
          const containerRect = container.getBoundingClientRect();
          const targetRect = targetSection.getBoundingClientRect();
          const scrollOffset = targetRect.top - containerRect.top + container.scrollTop;

          container.scrollTo({
            top: scrollOffset,
            behavior: 'smooth'
          });
        }
      }
    }

    // 月セクションのクリックイベントを監視
    document.addEventListener('click', function(e) {
      const monthSection = e.target.closest('.deposit-month-section');
      if (monthSection && !e.target.closest('button') && !e.target.closest('input')) {
        const yearMonth = monthSection.getAttribute('data-year-month');
        if (yearMonth !== currentExpandedMonth) {
          expandMonth(yearMonth);
        }
      }
    });
  </script>
  @endpush
</x-app-layout>
