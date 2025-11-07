<!-- resources/views/clinic-users-info/cui-insurances-info/cii-registration.blade.php -->


<x-app-layout>
  <h2>{{ $title }}</h2>
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

  @php
    // モードに応じたフォームの送信先を設定
    if ($mode === 'create') {
      $formAction = route('clinic-users.insurances.confirm', $userId);
      $isEdit = false;
    } elseif ($mode === 'edit') {
      $formAction = route('clinic-users.insurances.edit.confirm', [$userId, $insurance->id]);
      $isEdit = true;
    } else { // duplicate
      $formAction = route('clinic-users.insurances.duplicate.confirm', [$userId, $insurance->id]);
      $isEdit = true;
    }
  @endphp

  <form action="{{ $formAction }}" method="POST">
    @include('clinic-users.insurances.components.insurances-form', [
      'isEdit' => $isEdit,
      'insurance' => $insurance,
      'insurers' => $insurers ?? null,
      'submitLabel' => '登録確認へ',
      'cancelRoute' => route('clinic-users.insurances.index', $userId)
    ])
  </form>
</x-app-layout>
