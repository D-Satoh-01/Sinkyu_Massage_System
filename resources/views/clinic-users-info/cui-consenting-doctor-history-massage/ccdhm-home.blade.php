<!-- resources/views/clinic-users-info/cui-consenting-doctor-history-massage/ccdhm-home.blade.php -->


<x-app-layout>
  <h2>{{ $name }} 様の同意医師履歴（あんま・マッサージ）</h2>
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

  <!-- 同意医師履歴新規登録ボタン -->
  <a href="{{ route('cui-consenting-doctor-history-massage.registration', $id) }}">
  <button>同意医師履歴新規登録</button>
  </a>

  <!-- 同意医師履歴印刷ボタン -->
  <button type="button" id="printConsentingHistory" style="margin-left: 10px;">同意医師履歴印刷</button>
  <br><br>

  <!-- 同意医師履歴一覧テーブル -->
  <table id="consentingTable" class="table table-bordered table-striped">
  <thead>
    <tr>
    <th>同意医師名</th>
    <th>同意日</th>
    <th>同意開始日</th>
    <th>同意終了日</th>
    <th>データ登録日</th>
    <th>複製</th>
    <th>削除</th>
    </tr>
  </thead>
  <tbody>
    @forelse($consentingHistories as $history)
    <tr>
      <td>
      <a href="{{ route('cui-consenting-doctor-history-massage.edit', ['id' => $id, 'history_id' => $history->id]) }}">{{ $history->consenting_doctor_name }} [編集]</a>
      </td>
      <td data-order="{{ $history->consenting_date ? strtotime($history->consenting_date) : 0 }}">
      @if($history->consenting_date)
        {{ \Carbon\Carbon::parse($history->consenting_date)->format('Y/m/d') }}
      @endif
      </td>
      <td data-order="{{ $history->consenting_start_date ? strtotime($history->consenting_start_date) : 0 }}">
      @if($history->consenting_start_date)
        {{ \Carbon\Carbon::parse($history->consenting_start_date)->format('Y/m/d') }}
      @endif
      </td>
      <td data-order="{{ $history->consenting_end_date ? strtotime($history->consenting_end_date) : 0 }}">
      @if($history->consenting_end_date)
        {{ \Carbon\Carbon::parse($history->consenting_end_date)->format('Y/m/d') }}
      @endif
      </td>
      <td data-order="{{ strtotime($history->created_at) }}">
      {{ \Carbon\Carbon::parse($history->created_at)->format('Y/m/d') }}
      </td>
      <td>
      <a href="{{ route('cui-consenting-doctor-history-massage.duplicate', ['id' => $id, 'history_id' => $history->id]) }}">[複製]</a>
      </td>
      <td>
      <form action="{{ route('cui-consenting-doctor-history-massage.delete', ['id' => $id, 'history_id' => $history->id]) }}" method="POST" class="delete-form" style="display: inline;">
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
      console.log('テーブルのヘッダー列数:', $('#consentingTable thead tr th').length);
      console.log('テーブルの最初の行の列数:', $('#consentingTable tbody tr:first td').length);

      // データがない場合はDataTablesを初期化しない
      const hasData = $('#consentingTable tbody tr').length > 0 &&
                      !$('#consentingTable tbody tr:first td[colspan]').length;

      console.log('データがあるか:', hasData);

      if (hasData) {
        $('#consentingTable').DataTable({
          language: {
            url: '{{ asset('js/dataTables-ja.json') }}'
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

      // 同意医師履歴印刷
      $('#printConsentingHistory').on('click', function() {
        const url = '{{ route('cui-consenting-doctor-history-massage.print-history', $id) }}';
        const windowName = 'ConsentingHistoryPDF_' + new Date().getTime();
        const windowFeatures = 'popup=yes,width=1200,height=800,left=100,top=100,menubar=yes,toolbar=yes,location=yes,status=yes,scrollbars=yes,resizable=yes';
        window.open(url, windowName, windowFeatures);
      });
    });
  </script>
  @endpush
</x-app-layout>
