<!-- resources/views/clinic-users-info/cui-edit.blade.php -->


<x-app-layout>
  <h2>利用者情報編集</h2><br><br>

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

  @include('clinic-users-info._form', [
    'action' => route('cui-edit.confirm'),
    'sessionKey' => 'edit_data',
    'clinicUser' => $clinicUser,
    'isEdit' => true,
    'includeId' => true,
  ])
</x-app-layout>