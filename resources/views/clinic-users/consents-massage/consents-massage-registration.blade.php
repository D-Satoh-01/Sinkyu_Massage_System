<!-- resources/views/clinic-users-info/cui-consenting-doctor-history-massage/ccdhm-registration.blade.php -->


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
      $formAction = route('clinic-users.consents-massage.confirm', $id);
    } elseif ($mode === 'edit') {
      $formAction = route('clinic-users.consents-massage.edit.confirm', [$id, $history->id]);
    } else { // duplicate
      $formAction = route('clinic-users.consents-massage.duplicate.confirm', [$id, $history->id]);
    }
  @endphp

  @if($mode === 'duplicate')
  <div class="alert alert-warning">
    <strong>複製元の履歴:</strong> {{ $history->consenting_doctor_name }}
    (同意日: {{ $history->consenting_date?->format('Y年m月d日') ?? '未設定' }})
  </div>
  @endif

  <form action="{{ $formAction }}" method="POST">
    @include('clinic-users.consents-massage.components.consents-massage-form', [
      'history' => $history ?? null,
      'submitLabel' => '登録確認へ',
      'cancelRoute' => route('clinic-users.consents-massage.index', $id)
    ])
  </form>
</x-app-layout>
