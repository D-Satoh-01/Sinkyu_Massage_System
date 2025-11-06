<!-- resources/views/clinic-users-info/cui-registration.blade.php -->


<x-app-layout>
  <h2>{{ $title }}</h2><br><br>

  @if(session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
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

  @php
    // モードに応じたフォームの送信先を設定
    if ($mode === 'create') {
      $formAction = route('clinic-users-info.confirm');
      $sessionKey = 'registration_data';
      $isEdit = false;
      $includeId = false;
    } else { // edit
      $formAction = route('clinic-users-info.edit.confirm');
      $sessionKey = 'edit_data';
      $isEdit = true;
      $includeId = true;
    }
  @endphp

  @include('clinic-users-info.cui-form', [
  'action' => $formAction,
  'sessionKey' => $sessionKey,
  'clinicUser' => $clinicUser,
  'isEdit' => $isEdit,
  'includeId' => $includeId,
  ])
</x-app-layout>
