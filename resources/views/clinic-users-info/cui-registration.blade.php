<!-- resources/views/clinic-users-info/cui-registration.blade.php -->


<x-app-layout>
  <h2>利用者新規登録</h2><br><br>

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

  @include('clinic-users-info.cui-form', [
  'action' => route('cui-registration.confirm'),
  'sessionKey' => 'registration_data',
  'clinicUser' => null,
  'isEdit' => false,
  'includeId' => false,
  ])
</x-app-layout>
