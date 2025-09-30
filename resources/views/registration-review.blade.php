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
            @elseif($key === 'is_redeemed')
              {{ $data[$key] ? 'あり' : 'なし' }}
            @elseif($key === 'birthday')
              {{ date('Y年n月j日', strtotime($data[$key])) }}
            @else
              {{ $data[$key] }}
            @endif
          @else
            {{-- 空欄の場合 --}}
            &nbsp;
          @endif
        </div>
      </div>
    @endforeach
  </div>

  <br>

  <form action="{{ route($back_route) }}" method="GET" style="display: inline-block;">
    <button type="submit" class="me-3">◀ 戻る</button>
  </form>

  <form action="{{ route($store_route) }}" method="POST" style="display: inline-block;">
    @csrf
    <button type="submit" class="me-3">登録する</button>
  </form>
</x-app-layout>