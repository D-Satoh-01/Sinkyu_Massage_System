<!-- resources/views/clinic-users-info/cui-index.blade.php -->


<x-app-layout>
  <h2>利用者情報</h2>

  <br><br>

  <a href="{{ route('clinic-users-info.create') }}">
  <button>利用者新規登録</button>
  </a>

  <br><br>

  <!-- 利用者一覧テーブル -->
  <table id="userTable" class="table table-bordered table-striped">
  <thead>
    <tr>
    <th>ID</th>
    <th>名前 [編集] / カナ</th>
    <th>生年月日</th>
    <th>住所 / TEL</th>
    <th>データ登録日</th>
    <th>各種編集</th>
    <th>削除</th>
    </tr>
  </thead>
  <tbody>
    @forelse($clinicUsers as $user)
    <tr>
      <td>{{ $user->id }}</td>
      <td>
      <a href="{{ route('clinic-users-info.edit', ['id' => $user->id]) }}">{{ $user->clinic_user_name }} [編集]</a><br>
      {{ $user->furigana }}
      </td>
      <td>
      @if(!empty($user->birthday))
        {{ optional($user->birthday)->format('Y/m/d') }}
        ({{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->age : '' }}才)
      @endif
      </td>
      <td>
      @if(!empty($user->postal_code))
        〒{{ $user->postal_code }}<br>
      @endif
      {{ $user->address_1 }} {{ $user->address_2 }} {{ $user->address_3 }}
      </td>
      <td data-order="{{ $user->created_at ? $user->created_at->timestamp : 0 }}">
      {{ optional($user->created_at)->format('Y/m/d') }}<br>
      {{ optional($user->created_at)->format('H:i') }}
      </td>
      <td>
      <a href="{{ route('clinic-users-info.insurances-info.index', ['id' => $user->id]) }}">保険情報</a><br>
      <a href="{{ route('clinic-users-info.consenting-doctor-history-massage.index', ['id' => $user->id]) }}">同意医師履歴（あんま・マッサージ）</a><br>
      <a href="{{ route('clinic-users-info.consenting-doctor-history-acupuncture.index', ['id' => $user->id]) }}">同意医師履歴（はり・きゅう）</a><br>
      <a href="{{ route('clinic-users-info.plans-info.index', ['id' => $user->id]) }}">計画情報</a>
      </td>
      <td>
      <form action="{{ route('clinic-users-info.delete', ['id' => $user->id]) }}" method="POST" class="delete-form" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="delete-btn" style="background: none; border: none; color: #0d6efd; cursor: pointer;">削除</button>
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
      $('#userTable').DataTable({
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
          { orderable: false, targets: [5, 6] }
        ]
      });

      // 削除確認
      $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
          this.submit();
        }
      });
    });
  </script>
  @endpush
</x-app-layout>