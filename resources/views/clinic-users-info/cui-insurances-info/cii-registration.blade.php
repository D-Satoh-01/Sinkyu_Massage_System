<!-- resources/views/clinic-users-info/cui-insurances-info/cii-registration.blade.php -->


<x-app-layout>
  <h2>{{ $name }} 様の保険情報新規登録</h2>
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

  <form action="{{ route('clinic-users-info.insurances-info.confirm', $id) }}" method="POST">
    @include('clinic-users-info.cui-insurances-info.components.insurance-form', [
      'isEdit' => false,
      'insurance' => null,
      'insurers' => $insurers,
      'submitLabel' => '登録確認へ',
      'cancelRoute' => route('clinic-users-info.insurances-info.index', $id)
    ])
  </form>
</x-app-layout>
