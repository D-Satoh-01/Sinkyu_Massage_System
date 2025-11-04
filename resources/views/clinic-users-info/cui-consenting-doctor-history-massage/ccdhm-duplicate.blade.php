<!-- resources/views/clinic-users-info/cui-consenting-doctor-history-massage/ccdhm-duplicate.blade.php -->


<x-app-layout>
  <h2>{{ $name }} 様の同意医師履歴（あんま・マッサージ）複製</h2>
  <br><br>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="alert alert-warning">
    <strong>複製元の履歴:</strong> {{ $history->consenting_doctor_name }}
    (同意日: {{ $history->consenting_date?->format('Y年m月d日') ?? '未設定' }})
  </div>

  <form action="{{ route('cui-consenting-doctor-history-massage.duplicate.confirm', [$id, $history->id]) }}" method="POST">
    @include('clinic-users-info.cui-consenting-doctor-history-massage.components.consenting-form', [
      'history' => $history,
      'submitLabel' => '複製確認へ',
      'cancelRoute' => route('cui-consenting-doctor-history-massage', $id)
    ])
  </form>
</x-app-layout>
