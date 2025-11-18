<!-- resources/views/master/documents/documents_registration.blade.php -->

<x-app-layout>
  <h2>{{ $title ?? '文書新規登録' }}</h2><br><br>

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
      $formAction = route('master.documents.store');
      $submitLabel = '登録';
      $item = (object)[];
    } elseif ($mode === 'duplicate') {
      $formAction = route('master.documents.duplicate.store');
      $submitLabel = '複製登録';
      $item = $document;
    } else { // edit
      $formAction = route('master.documents.update', $document->id);
      $submitLabel = '更新';
      $item = $document;
    }
  @endphp

  <form action="{{ $formAction }}" method="POST">
    @include('master.documents.components.documents_form', [
      'item' => $item,
      'categories' => $categories,
      'submitLabel' => $submitLabel,
      'cancelRoute' => route('master.documents.index')
    ])
  </form>
</x-app-layout>
