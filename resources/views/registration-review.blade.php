<!-- resources/views/registration-review.blade.php -->

<x-app-layout>
  <h2>{{ $page_title }}</h2><br><br>

  <p>入力内容の登録を行います。</p><br>

  <table>
    <tbody>
      @foreach($data as $key => $value)
        @if($value !== null && $value !== '')
          <tr>
            <th>{{ $labels[$key] ?? $key }}</th>
            <td>
              @if($key === 'gender_id')
                {{ $value == 1 ? '男性' : ($value == 2 ? '女性' : '') }}
              @elseif($key === 'is_redeemed')
                {{ $value ? 'あり' : 'なし' }}
              @elseif($key === 'birthday')
                {{ $value ? date('Y年n月j日', strtotime($value)) : '' }}
              @else
                {{ $value }}
              @endif
            </td>
          </tr>
        @endif
      @endforeach
    </tbody>
  </table>

  <br>

  <form action="{{ route($back_route) }}" method="GET" style="display: inline;">
    <button type="submit">◀ 戻る</button>
  </form>

  <form action="{{ route($store_route) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit">登録する</button>
  </form>
</x-app-layout>