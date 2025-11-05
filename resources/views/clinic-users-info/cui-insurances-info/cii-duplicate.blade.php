<x-app-layout>
  <h2>{{ $user->clinic_user_name }} 様の保険情報複製</h2>
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

  <form method="POST" action="{{ route('clinic-users-info.insurances-info.duplicate.confirm', [$user->id, $insurance->id]) }}">
    @include('clinic-users-info.cui-insurances-info.components.insurance-form', [
      'isEdit' => true,
      'insurance' => $insurance,
      'submitLabel' => '登録確認へ',
      'cancelRoute' => route('clinic-users-info.insurances-info.index', $user->id)
    ])
  </form>
</x-app-layout>
