<!-- resources/views/registration-done.blade.php -->


<x-app-layout>
  <h2>{{ $page_title }}</h2><br><br>

  <p>{{ $message }}</p><br>

  <a href="{{ route($home_route) }}">
    <button type="button">◀ 利用者情報一覧に戻る</button>
  </a>

  @if($list_route)
    <a href="{{ route($list_route) }}">
      <button type="button">一覧を見る</button>
    </a>
  @endif
</x-app-layout>
