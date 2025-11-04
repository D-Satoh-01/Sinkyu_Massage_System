<!-- resources/views/clinic-users-info/cui-consenting-doctor-history-massage/ccdhm-edit.blade.php -->


<x-app-layout>
  <h2>{{ $name }} 様の同意医師履歴（あんま・マッサージ）編集</h2>
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

  <form action="{{ route('cui-consenting-doctor-history-massage.edit.confirm', [$id, $history->id]) }}" method="POST">
    @include('clinic-users-info.cui-consenting-doctor-history-massage.components.consenting-form', [
      'history' => $history,
      'submitLabel' => '更新確認へ',
      'cancelRoute' => route('cui-consenting-doctor-history-massage', $id)
    ])
  </form>
</x-app-layout>
