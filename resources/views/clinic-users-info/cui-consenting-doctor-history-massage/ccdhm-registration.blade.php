<!-- resources/views/clinic-users-info/cui-consenting-doctor-history-massage/ccdhm-registration.blade.php -->


<x-app-layout>
  <h2>{{ $name }} 様の同意医師履歴（あんま・マッサージ）新規登録</h2>
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

  <form action="{{ route('clinic-users-info.consenting-doctor-history-massage.confirm', $id) }}" method="POST">
    @include('clinic-users-info.cui-consenting-doctor-history-massage.components.consenting-form', [
      'history' => null,
      'submitLabel' => '登録確認へ',
      'cancelRoute' => route('clinic-users-info.consenting-doctor-history-massage.index', $id)
    ])
  </form>
</x-app-layout>
