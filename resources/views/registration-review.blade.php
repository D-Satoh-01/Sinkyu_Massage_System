<!-- resources/views/registration-review.blade.php -->


<x-app-layout>
  <h2>{{ $page_title }}</h2><br><br>

  <p>{{ $registration_message }}</p><br>

  <div>
  @foreach($labels as $key => $label)
    <div class="mb-3">
    <div class="fw-semibold">{{ $label }}</div>
    <div>
      @if(isset($data[$key]) && $data[$key] !== null && $data[$key] !== '')
      @if($key === 'gender_id')
        {{ $data[$key] == 1 ? '男性' : ($data[$key] == 2 ? '女性' : '') }}
      @elseif($key === 'is_redeemed' || $key === 'reimbursement_target' || $key === 'is_healthcare_subsidized')
        {{ $data[$key] ? '対象' : '非対象' }}
      @elseif(in_array($key, ['birthday', 'qualification_date', 'certification_date', 'issue_date', 'expiration_date', 'license_acquisition_date', 'expiry_date', 'consenting_date', 'consenting_start_date', 'consenting_end_date', 'benefit_period_start_date', 'benefit_period_end_date', 'first_care_date', 'reconsenting_expiry', 'onset_and_injury_date']))
        @php
          $dateValue = $data[$key];
          if (is_object($dateValue) && method_exists($dateValue, 'format')) {
            echo $dateValue->format('Y年n月j日');
          } elseif (is_string($dateValue) && $dateValue !== '') {
            echo date('Y年n月j日', strtotime($dateValue));
          } else {
            echo $dateValue;
          }
        @endphp
      @else
        {{ $data[$key] }}
      @endif
      @else
      &nbsp;
      @endif
    </div>
    </div>
  @endforeach
  </div>

  <br>

  @if(isset($back_insurance_id))
  <a href="{{ route($back_route, ['id' => $back_id, 'insurance_id' => $back_insurance_id]) }}">
    <button class="me-3">◀ 戻る</button>
  </a>
  @elseif(isset($back_history_id))
  <a href="{{ route($back_route, ['id' => $back_id, 'history_id' => $back_history_id]) }}">
    <button class="me-3">◀ 戻る</button>
  </a>
  @elseif(isset($back_id))
  <a href="{{ route($back_route, ['id' => $back_id]) }}">
    <button class="me-3">◀ 戻る</button>
  </a>
  @else
  <form action="{{ route($back_route) }}" method="GET" style="display: inline-block;">
    <button type="submit" class="me-3">◀ 戻る</button>
  </form>
  @endif

  @if(isset($back_insurance_id))
  <form action="{{ route($store_route, ['id' => $back_id, 'insurance_id' => $back_insurance_id]) }}" method="POST" style="display: inline-block;">
  @elseif(isset($back_history_id))
  <form action="{{ route($store_route, ['id' => $back_id, 'history_id' => $back_history_id]) }}" method="POST" style="display: inline-block;">
  @elseif(isset($back_id))
  <form action="{{ route($store_route, ['id' => $back_id]) }}" method="POST" style="display: inline-block;">
  @else
  <form action="{{ route($store_route) }}" method="POST" style="display: inline-block;">
  @endif
  @csrf
  <button type="submit" class="me-3">登録する</button>
  </form>
</x-app-layout>