<!-- resources/views/submaster/self-paid-fees.blade.php -->

<x-app-layout>
  <h2>自費施術料金</h2>

  @if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
  @endif

  <table id="selfPaidFeesTable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 10%;">ID</th>
        <th style="width: 35%;">名称</th>
        <th style="width: 25%;">金額</th>
        <th style="width: 15%;">更新</th>
        <th style="width: 15%;">削除</th>
      </tr>
    </thead>
    <tbody>
      <!-- 新規登録行 -->
      <tr class="new-entry-row">
        <td>新規登録</td>
        <td>
          <input type="text" name="name" value="" required style="width: 95%;" form="form-new">
        </td>
        <td>
          <input type="number" name="amount" value="" required min="0" style="width: 95%;" form="form-new">
        </td>
        <td colspan="2" style="text-align: center;">
          <form action="{{ route('submaster.self-paid-fees.store') }}" method="POST" id="form-new" class="d-inline">
            @csrf
            <button type="submit">新規登録</button>
          </form>
        </td>
      </tr>

      <!-- 既存データ行 -->
      @foreach($items as $item)
      <tr>
        <td>{{ $item->id }}</td>
        <td>
          <form action="{{ route('submaster.self-paid-fees.update', $item->id) }}" method="POST" id="form-{{ $item->id }}">
            @csrf
            <input type="text" name="name" value="{{ $item->name }}" required style="width: 95%;">
        </td>
        <td>
            <input type="number" name="amount" value="{{ $item->amount }}" required min="0" style="width: 95%;">
          </form>
        </td>
        <td style="text-align: center;">
          <button type="submit" form="form-{{ $item->id }}">更新</button>
        </td>
        <td style="text-align: center;">
          <form action="{{ route('submaster.self-paid-fees.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除する？');">
            @csrf
            @method('DELETE')
            <button type="submit">削除</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @push('scripts')
  <script>
    $(document).ready(function() {
      var newEntryRow = $('.new-entry-row').detach();

      var table = $('#selfPaidFeesTable').DataTable({
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
          { orderable: false, targets: [3, 4] }
        ],
        drawCallback: function() {
          $(this.api().table().body()).prepend(newEntryRow);
        }
      });

      $(table.table().body()).prepend(newEntryRow);
    });
  </script>
  @endpush
</x-app-layout>
