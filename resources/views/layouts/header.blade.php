<!-- resources/views/layouts/header.blade.php -->


<div class="d-flex align-items-center gap-3 fw-bold">
  <a href="{{route('home')}}">ホーム</a>

  <form method="POST" action="{{ route('logout') }}" class="m-0">
  @csrf
  <x-responsive-nav-link :href="route('logout')"
    onclick="event.preventDefault();
    this.closest('form').submit();"
    class="fw-bold" style="padding: 0 !important">
    {{ __('ログアウト') }}
  </x-responsive-nav-link>
  </form>
</div>

<hr class="border-black opacity-50 border-2">
