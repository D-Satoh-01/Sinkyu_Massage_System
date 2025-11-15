<!-- resources/views/master/treatment-fees/treatment-fees_registration.blade.php -->

<x-app-layout>
  <h2>{{ $title ?? '施術料金新規登録' }}</h2><br><br>

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
    if (($mode ?? 'create') === 'create') {
      $formAction = route('master.treatment-fees.store');
      $submitLabel = '登録確認へ';
    } else { // edit
      $formAction = route('master.treatment-fees.update', $item->id);
      $submitLabel = '更新';
    }
  @endphp

  <form action="{{ $formAction }}" method="POST">
    @include('master.treatment-fees.components.treatment-fees_form', [
      'item' => $item ?? (object)[],
      'submitLabel' => $submitLabel,
      'cancelRoute' => route('master.treatment-fees.index')
    ])
  </form>
</x-app-layout>
