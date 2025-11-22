<!-- resources/views/records/records_index.blade.php -->

<x-app-layout>
  <h2>実績データ</h2>

  <br><br>

  <!-- 利用者選択フォーム -->
  <form method="GET" action="{{ route('records.index') }}" id="filterForm">
    <div style="margin-bottom: 20px;">
      <label for="clinic_user_id"></label>
      <select name="clinic_user_id" id="clinic_user_id" onchange="document.getElementById('filterForm').submit();">
        <option value="">╌╌╌</option>
        @foreach($clinicUsers as $user)
          <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
            {{ $user->last_name }} {{ $user->first_name }} ({{ $user->last_kana }} {{ $user->first_kana }})
          </option>
        @endforeach
      </select>
      <br>
      <button type="button" onclick="openUserSearchPopup()">利用者検索</button>
    </div>
  </form>

  <script>
    function openUserSearchPopup() {
      const width = 600;
      const height = 400;
      const left = (screen.width - width) / 2;
      const top = (screen.height - height) / 2;
      window.open(
        '{{ route("user.search") }}',
        'userSearchPopup',
        `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
      );
    }
  </script>

</x-app-layout>
