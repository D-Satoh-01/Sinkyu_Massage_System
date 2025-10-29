<x-app-layout>
  <h2>{{ $user->clinic_user_name }} 様の保険情報編集</h2>
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

  <form method="POST" action="{{ route('cii-edit.update', [$user->id, $insurance->id]) }}">
    @include('clinic-users-info.cui-insurances-info.components.insurance-form', [
      'isEdit' => true,
      'insurance' => $insurance,
      'submitLabel' => '更新',
      'cancelRoute' => route('cui-insurances-info', $user->id)
    ])
  </form>
</x-app-layout>
