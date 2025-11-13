<!-- resources/views/therapists/therapists_registration.blade.php -->


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
      $formAction = route('therapists.confirm');
    } else { // edit
      $formAction = route('therapists.edit.confirm', $therapist->id);
    }
  @endphp

  <form action="{{ $formAction }}" method="POST">
    @include('therapists.components.therapists_form', [
      'therapist' => $therapist,
      'submitLabel' => '登録確認へ',
      'cancelRoute' => route('therapists.index')
    ])
  </form>
</x-app-layout>
