<!-- resources/views/clinic-users-info/cui-insurances-info/cii-home.blade.php -->


<x-app-layout>
  <h2>{{ $name }} 様の保険情報</h2>
  <br><br>

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

  <!-- 保険情報新規登録ボタン -->
  <a href="{{ route('cui-insurances-info.registration', $id) }}">
    <button type="button">保険情報新規登録</button>
  </a>
  <br><br>

  <!-- 保険情報一覧テーブル -->
  <table id="insuranceTable" style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
    <thead>
      <tr style="background-color: #eeeeee;">
        <th style="border: 1px solid #000; padding: 8px;">保険区分</th>
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="insured_number">
            被保険番号
            <span id="sort-insured_number">{{ ($sortBy ?? 'created_at') == 'insured_number' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="license_acquisition_date">
            資格取得日
            <span id="sort-license_acquisition_date">{{ ($sortBy ?? 'created_at') == 'license_acquisition_date' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="expiry_date">
            有効期限
            <span id="sort-expiry_date">{{ ($sortBy ?? 'created_at') == 'expiry_date' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="created_at">
            データ登録日
            <span id="sort-created_at">{{ ($sortBy ?? 'created_at') == 'created_at' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">複製</th>
        <th style="border: 1px solid #000; padding: 8px;">削除</th>
      </tr>
    </thead>
    <tbody>
      @forelse($insurances as $insurance)
        <tr>
          <td style="border: 1px solid #000; padding: 8px;">
            @if($insurance->insurer && strlen($insurance->insurer->insurer_number) == 6)
              <a href="{{ route('cui-insurances-info.edit', ['id' => $id, 'insurance_id' => $insurance->id]) }}">国民健康保険 [編集]</a>
            @elseif($insurance->insurer && strlen($insurance->insurer->insurer_number) == 8)
              <a href="{{ route('cui-insurances-info.edit', ['id' => $id, 'insurance_id' => $insurance->id]) }}">組合保険 [編集]</a>
            @else
              <a href="{{ route('cui-insurances-info.edit', ['id' => $id, 'insurance_id' => $insurance->id]) }}">保険 [編集]</a>
            @endif
          </td>
          <td style="border: 1px solid #000; padding: 8px;">{{ $insurance->insured_number }}</td>
          <td style="border: 1px solid #000; padding: 8px;">
            @if($insurance->license_acquisition_date)
              {{ $insurance->license_acquisition_date->format('Y/m/d') }}
            @endif
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            @if($insurance->expiry_date)
              {{ $insurance->expiry_date->format('Y/m/d') }}
            @endif
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            {{ $insurance->created_at->format('Y/m/d') }}
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            <form action="{{ route('cui-insurances-info.duplicate', ['id' => $id, 'insurance_id' => $insurance->id]) }}" method="POST" style="display: inline;">
              @csrf
              <button type="submit" style="background: none; border: none; color: #0d6efd; cursor: pointer;">[複製]</button>
            </form>
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            <form action="{{ route('cui-insurances-info.delete', ['id' => $id, 'insurance_id' => $insurance->id]) }}" method="POST" class="delete-form" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="delete-btn" style="background: none; border: none; color: #0d6efd; cursor: pointer;">[削除]</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" style="border: 1px solid #000; padding: 8px; text-align: center;">データがありません</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // 削除確認
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
          form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
              form.submit();
            }
          });
        });

        // クライアントサイド並び替え
        const table = document.getElementById('insuranceTable');
        if (!table) return;
        const tbody = table.querySelector('tbody');
        const headers = Array.from(table.querySelectorAll('th a[data-sort]'));
        const ths = Array.from(table.querySelectorAll('thead th'));

        // 初期ソート表示（サーバー側で渡された場合に合わせる）
        try {
          const initSort = window.initialSort || '{{ $sortBy ?? "created_at" }}';
          const initOrder = window.initialOrder || '{{ $sortOrder ?? "desc" }}';
          const span = document.getElementById('sort-' + initSort);
          if (span) span.textContent = initOrder === 'asc' ? '▴' : '▾';
        } catch (e) { /* ignore */ }

        const sortState = {}; // column -> 'asc'|'desc'

        function parseCellValue(text) {
          if (!text) return null;
          const t = text.trim();
          // 日付 (YYYY/MM/DD) を検出
          if (/^\d{4}\/\d{1,2}\/\d{1,2}/.test(t)) {
            // Date は 'YYYY/MM/DD' を 'YYYY-MM-DD' にして生成
            return new Date(t.replace(/\//g, '-'));
          }
          // 数字 (被保険者番号など) --- 数字のみ抽出して比較
          const num = parseInt(t.replace(/[^0-9]/g, ''), 10);
          if (!isNaN(num) && num.toString().length > 0) return num;
          return t.toLowerCase();
        }

        headers.forEach(header => {
          header.addEventListener('click', function(e) {
            e.preventDefault();
            const column = this.getAttribute('data-sort');
            const th = this.closest('th');
            const colIndex = ths.indexOf(th);
            const current = sortState[column] || (window.initialSort === column ? (window.initialOrder || '{{ $sortOrder ?? "desc" }}') : 'desc');
            const newOrder = current === 'asc' ? 'desc' : 'asc';
            sortState[column] = newOrder;

            // ヘッダ矢印を更新（他列はクリア）
            headers.forEach(h => {
              const id = 'sort-' + h.getAttribute('data-sort');
              const s = document.getElementById(id);
              if (s) s.textContent = '';
            });
            const span = document.getElementById('sort-' + column);
            if (span) span.textContent = newOrder === 'asc' ? '▴' : '▾';

            // 並び替え
            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.sort((a, b) => {
              const aCell = a.children[colIndex] ? a.children[colIndex].textContent : '';
              const bCell = b.children[colIndex] ? b.children[colIndex].textContent : '';
              const va = parseCellValue(aCell);
              const vb = parseCellValue(bCell);

              // null/empty handling
              if (va === null && vb === null) return 0;
              if (va === null) return newOrder === 'asc' ? 1 : -1;
              if (vb === null) return newOrder === 'asc' ? -1 : 1;

              // 日付比較
              if (va instanceof Date && vb instanceof Date) {
                return newOrder === 'asc' ? va - vb : vb - va;
              }
              // 数値比較
              if (typeof va === 'number' && typeof vb === 'number') {
                return newOrder === 'asc' ? va - vb : vb - va;
              }
              // 文字列比較
              if (va < vb) return newOrder === 'asc' ? -1 : 1;
              if (va > vb) return newOrder === 'asc' ? 1 : -1;
              return 0;
            });

            // 並べ替え後、tbody に行を再追加
            rows.forEach(r => tbody.appendChild(r));
          });
        });
      });
    </script>
  @endpush
</x-app-layout>

