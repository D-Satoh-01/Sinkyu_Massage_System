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
        <th style="border: 1px solid #000; padding: 8px;">被保険番号</th>
        <th style="border: 1px solid #000; padding: 8px;">資格取得日</th>
        <th style="border: 1px solid #000; padding: 8px;">有効期限</th>
        <th style="border: 1px solid #000; padding: 8px;">データ登録日</th>
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
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
          form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
              form.submit();
            }
          });
        });
      });
    </script>
  @endpush
</x-app-layout>

