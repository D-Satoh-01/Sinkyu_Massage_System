<!-- resources/views/master/documents/documents_index.blade.php -->

<x-app-layout>
  <h2>文書</h2>
  <a href="{{ route('master.index') }}">←マスター登録に戻る</a>
  <br><br>

  @if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
  @endif

  <!-- 新規登録ボタン -->
  <div style="margin-bottom: 15px;">
    <button type="button" id="newDocumentBtn">
      新規登録
    </button>
  </div>

  <table id="documentsTable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 15%;">文書カテゴリ</th>
        <th style="width: 40%;">文書名称</th>
        <th style="width: 15%;">登録日時</th>
        <th style="width: 10%;">プレビュー</th>
        <th style="width: 10%;">編集</th>
        <th style="width: 10%;">削除</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $item)
      <tr>
        <td>{{ $item->document_category_id }}</td>
        <td>{{ $item->document_content }}</td>
        <td>{{ $item->created_at ? $item->created_at->format('Y-m-d H:i') : '' }}</td>
        <td style="text-align: center;">
          <button type="button" class="preview-btn" data-id="{{ $item->id }}">プレビュー</button>
        </td>
        <td style="text-align: center;">
          <button type="button" class="edit-btn" data-id="{{ $item->id }}">編集</button>
        </td>
        <td style="text-align: center;">
          <button type="button" class="delete-btn" data-id="{{ $item->id }}">削除</button>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @push('scripts')
  <script>
    $(document).ready(function() {
      var table = $('#documentsTable').DataTable({
        language: {
          url: '{{ asset('js/dataTables-ja.json') }}',
          paginate: {
            previous: '◂ 前へ',
            next: '次へ ▸'
          }
        },
        order: [[2, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        columnDefs: [
          { orderable: false, targets: [3, 4, 5] }
        ]
      });

      // 新規登録ボタン
      $('#newDocumentBtn').on('click', function() {
        window.location.href = '{{ route('master.documents.create') }}';
      });

      // 編集ボタン
      $('.edit-btn').on('click', function() {
        var id = $(this).data('id');
        alert('編集機能は後で実装予定: ID=' + id);
      });

      // プレビューボタン
      $('.preview-btn').on('click', function() {
        var id = $(this).data('id');
        window.open('{{ route('master.documents.preview', '') }}/' + id, '_blank');
      });

      // 削除ボタン
      $('.delete-btn').on('click', function() {
        var id = $(this).data('id');
        if(confirm('本当に削除する？')) {
          alert('削除機能は後で実装予定: ID=' + id);
        }
      });
    });
  </script>
  @endpush
</x-app-layout>
