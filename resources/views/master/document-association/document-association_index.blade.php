<!-- resources/views/master/standard-documents/index.blade.php -->

<x-app-layout>
  <h2>現在の登録済み標準文書の確認および関連付け</h2>
  <a href="{{ route('master.index') }}">←マスター登録に戻る</a>
  <br><br>

  @if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
  @endif

  <table id="standardDocumentsTable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 10%;">ID</th>
        <th style="width: 25%;">文書名</th>
        <th style="width: 20%;">関連付けタイプ</th>
        <th style="width: 15%;">関連付けID</th>
        <th style="width: 30%;">関連付け</th>
      </tr>
    </thead>
    <tbody>
      @foreach($documents as $document)
      <tr>
        <td>{{ $document->id }}</td>
        <td>{{ $document->document_name ?? '未設定' }}</td>
        <td>
          <form action="{{ route('master.standard-documents.associate', $document->id) }}" method="POST" id="form-{{ $document->id }}">
            @csrf
            <input type="text" name="associated_type" value="{{ $document->associated_type ?? '' }}" placeholder="タイプ" style="width: 95%;">
        </td>
        <td>
            <input type="number" name="associated_id" value="{{ $document->associated_id ?? '' }}" placeholder="ID" min="0" style="width: 95%;">
          </form>
        </td>
        <td style="text-align: center;">
          <button type="submit" form="form-{{ $document->id }}">関連付け</button>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @push('scripts')
  <script>
    $(document).ready(function() {
      $('#standardDocumentsTable').DataTable({
        language: {
          url: '{{ asset('js/dataTables-ja.json') }}',
          paginate: {
            previous: '◂ 前へ',
            next: '次へ ▸'
          }
        },
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        columnDefs: [
          { orderable: false, targets: [4] }
        ]
      });
    });
  </script>
  @endpush
</x-app-layout>
