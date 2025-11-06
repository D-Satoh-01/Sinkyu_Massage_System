<!-- resources/views/clinic-users-info/cui-insurances-info/cii-index.blade.php -->


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
  <a href="{{ route('clinic-users-info.insurances-info.create', $id) }}">
  <button>保険情報新規登録</button>
  </a>

  <!-- 医療保険履歴印刷ボタン -->
  <button type="button" id="printInsuranceHistory" style="margin-left: 10px;">医療保険履歴印刷</button>
  <br><br>

  <!-- 保険情報一覧テーブル -->
  <table id="insuranceTable" class="table table-bordered table-striped">
  <thead>
    <tr>
    <th>保険区分</th>
    <th>被保険番号</th>
    <th>資格取得日</th>
    <th>有効期限</th>
    <th>データ登録日</th>
    <th>複製</th>
    <th>削除</th>
    </tr>
  </thead>
  <tbody>
    @forelse($insurances as $insurance)
    <tr>
      <td>
      @php
        $insurerNumberLength = strlen($insurance->insurer?->insurer_number ?? '');
      @endphp
      @if($insurerNumberLength == 6)
        <a href="{{ route('clinic-users-info.insurances-info.edit', ['id' => $id, 'insurance_id' => $insurance->id]) }}">国民健康保険 [編集]</a>
      @elseif($insurerNumberLength == 8)
        <a href="{{ route('clinic-users-info.insurances-info.edit', ['id' => $id, 'insurance_id' => $insurance->id]) }}">組合保険 [編集]</a>
      @else
        <a href="{{ route('clinic-users-info.insurances-info.edit', ['id' => $id, 'insurance_id' => $insurance->id]) }}">保険 [編集]</a>
      @endif
      </td>
      <td>{{ $insurance->insured_number }}</td>
      <td data-order="{{ $insurance->license_acquisition_date ? $insurance->license_acquisition_date->timestamp : 0 }}">
      @if($insurance->license_acquisition_date)
        {{ $insurance->license_acquisition_date->format('Y/m/d') }}
      @endif
      </td>
      <td data-order="{{ $insurance->expiry_date ? $insurance->expiry_date->timestamp : 0 }}">
      @if($insurance->expiry_date)
        {{ $insurance->expiry_date->format('Y/m/d') }}
      @endif
      </td>
      <td data-order="{{ $insurance->created_at->timestamp }}">
      {{ $insurance->created_at->format('Y/m/d') }}
      </td>
      <td>
      <a href="{{ route('clinic-users-info.insurances-info.duplicate', ['id' => $id, 'insurance_id' => $insurance->id]) }}">[複製]</a>
      </td>
      <td>
      <form action="{{ route('clinic-users-info.insurances-info.delete', ['id' => $id, 'insurance_id' => $insurance->id]) }}" method="POST" class="delete-form" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="delete-btn" style="background: none; border: none; color: #0d6efd; cursor: pointer;">[削除]</button>
      </form>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="7" class="text-center">データがありません</td>
    </tr>
    @endforelse
  </tbody>
  </table>

  @push('scripts')
  <script>
    $(document).ready(function() {
      // デバッグ: テーブル構造をチェック
      console.log('テーブルのヘッダー列数:', $('#insuranceTable thead tr th').length);
      console.log('テーブルの最初の行の列数:', $('#insuranceTable tbody tr:first td').length);

      // データがない場合はDataTablesを初期化しない
      const hasData = $('#insuranceTable tbody tr').length > 0 &&
                      !$('#insuranceTable tbody tr:first td[colspan]').length;

      console.log('データがあるか:', hasData);

      if (hasData) {
        $('#insuranceTable').DataTable({
          language: {
            url: '{{ asset('js/dataTables-ja.json') }}',
            paginate: {
              previous: '◂ 前へ',
              next: '次へ ▸'
            }
          },
          order: [[4, 'desc']], // データ登録日の降順
          pageLength: 10,
          lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
          columnDefs: [
            { orderable: false, targets: [5, 6] } // 複製・削除列はソート無効
          ]
        });
      } else {
        console.log('データがないため、DataTablesを初期化しませんでした');
      }

      // 削除確認
      $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
          this.submit();
        }
      });

      // 医療保険履歴印刷
      $('#printInsuranceHistory').on('click', function() {
        const url = '{{ route('clinic-users-info.insurances-info.print-history', $id) }}';
        const windowName = 'InsuranceHistoryPDF_' + new Date().getTime();
        const windowFeatures = 'popup=yes,width=1200,height=800,left=100,top=100,menubar=yes,toolbar=yes,location=yes,status=yes,scrollbars=yes,resizable=yes';
        window.open(url, windowName, windowFeatures);
      });
    });
  </script>
  @endpush
</x-app-layout>

