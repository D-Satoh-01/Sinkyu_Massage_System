<!-- resources/views/doctors-info/di-registration.blade.php -->


<x-app-layout>
  <h2>{{ $title }}</h2><br><br>

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
      $formAction = route('doctors-info.confirm');
    } elseif ($mode === 'edit') {
      $formAction = route('doctors-info.edit.confirm', $doctor->id);
    } else { // duplicate
      $formAction = route('doctors-info.duplicate.confirm');
    }
  @endphp

  <form action="{{ $formAction }}" method="POST">
    @if($mode === 'duplicate')
      <input type="hidden" name="source_doctor_id" value="{{ old('source_doctor_id', $doctor->id) }}">
    @endif

    @include('doctors-info.components.doctor-form', [
      'doctor' => $doctor,
      'medicalInstitutions' => $medicalInstitutions,
      'submitLabel' => '登録確認へ',
      'cancelRoute' => route('doctors-info.index')
    ])
  </form>
</x-app-layout>
