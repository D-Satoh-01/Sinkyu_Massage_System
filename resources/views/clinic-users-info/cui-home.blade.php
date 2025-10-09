<!-- resources/views/clinic-users-info/cui-home.blade.php -->


<x-app-layout>
  <h2>利用者情報</h2>
  
  <br><br>

  <a href="{{ route('cui-registration') }}">
    <button type="button">利用者新規登録</button>
  </a>

  <br><br>

  <!-- 表示件数切替えと検索 -->
  <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
    <div>
      <label for="per_page">表示件数：</label>
      <select name="per_page" id="per_page" onchange="changePerPage(this.value)">
        <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
      </select>
      <a>件</a>
    </div>

    <div>
      <input type="text" id="search" placeholder="検索" value="{{ $search ?? '' }}" onchange="changeSearch(this.value)">
    </div>
  </div>

  <!-- 利用者一覧テーブル -->
  <table id="userTable" style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
    <thead>
      <tr style="background-color: #eeeeee;">
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="id">
            ID
            <span id="sort-id">{{ ($sortBy ?? 'id') == 'id' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="clinic_user_name">
            名前 [編集] / カナ
            <span id="sort-clinic_user_name">{{ ($sortBy ?? 'id') == 'clinic_user_name' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="birthday">
            生年月日
            <span id="sort-birthday">{{ ($sortBy ?? 'id') == 'birthday' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="address_1">
            住所 / TEL
            <span id="sort-address_1">{{ ($sortBy ?? 'id') == 'address_1' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">
          <a href="#" data-sort="created_at">
            データ登録日
            <span id="sort-created_at">{{ ($sortBy ?? 'id') == 'created_at' ? (($sortOrder ?? 'desc') == 'asc' ? '▴' : '▾') : '' }}</span>
          </a>
        </th>
        <th style="border: 1px solid #000; padding: 8px;">各種編集</th>
        <th style="border: 1px solid #000; padding: 8px;">削除</th>
      </tr>
    </thead>
    <tbody id="tableBody">
      @forelse($clinicUsers ?? [] as $user)
        <tr data-id="{{ $user->id }}"
            data-name="{{ $user->clinic_user_name }}" 
            data-furigana="{{ $user->furigana }}"
            data-birthday="{{ $user->birthday ? $user->birthday->format('Y/m/d') : '' }}"
            data-address="{{ ($user->postal_code ? '〒'.$user->postal_code : '') . $user->address_1 . $user->address_2 . $user->address_3 }}"
            data-created="{{ $user->created_at->format('Y/m/d H:i') }}">
          <td style="border: 1px solid #000; padding: 8px;">{{ $user->id }}</td>
          <td style="border: 1px solid #000; padding: 8px;">
            <a href="{{ route('cui-edit', ['id' => $user->id]) }}">{{ $user->clinic_user_name }} [編集]</a><br>
            {{ $user->furigana }}
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            @if($user->birthday)
              {{ $user->birthday->format('Y/m/d') }}
              ({{ \Carbon\Carbon::parse($user->birthday)->age }}才)
            @endif
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            @if($user->postal_code)
              〒{{ $user->postal_code }}<br>
            @endif
            {{ $user->address_1 }} {{ $user->address_2 }} {{ $user->address_3 }}
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            {{ $user->created_at->format('Y/m/d') }}<br>
            {{ $user->created_at->format('H:i') }}
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            <a href="{{ route('cui-insurances-info', ['id' => $user->id]) }}">保険情報</a><br>
            <a href="{{ route('cui-consenting-doctor-history-massage', ['id' => $user->id]) }}">同意医師履歴（あんま・マッサージ）</a><br>
            <a href="{{ route('cui-consenting-doctor-history-acupuncture', ['id' => $user->id]) }}">同意医師履歴（はり・きゅう）</a><br>
            <a href="{{ route('cui-plans-info', ['id' => $user->id]) }}">計画情報</a>
          </td>
          <td style="border: 1px solid #000; padding: 8px;">
            <form action="{{ route('cui-delete', ['id' => $user->id]) }}" method="POST" class="delete-form" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="delete-btn" style="background: none; border: none; color: #0d6efd; cursor: pointer;">削除</button>
            </form>
          </td>
        </tr>
      @empty
        <tr id="noDataRow">
          <td colspan="7" style="border: 1px solid #000; padding: 8px; text-align: center;">データがありません</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <br>

  <!-- ページネーション -->
  @if(isset($clinicUsers) && $clinicUsers->hasPages())
    <div class="pagination-container">
      {{ $clinicUsers->appends(['per_page' => $perPage ?? 10, 'search' => $search ?? '', 'sort_by' => $sortBy ?? 'id', 'sort_order' => $sortOrder ?? 'desc'])->links() }}
    </div>
  @endif

  @push('scripts')
    <script>
      window.initialSort = '{{ $sortBy ?? "id" }}';
      window.initialOrder = '{{ $sortOrder ?? "desc" }}';
      window.initialLimit = {{ $perPage ?? 10 }};
    </script>
    <script src="{{ asset('js/cui-home.js') }}"></script>
  @endpush
</x-app-layout>