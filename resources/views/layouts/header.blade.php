<!-- resources/views/layouts/header.blade.php -->


<div style="display: flex; align-items: center; gap: 1rem; font-weight: bold;">
  <a href="{{route('home')}}">ホーム</a>

  <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
    @csrf
    <x-responsive-nav-link :href="route('logout')"
      onclick="event.preventDefault();
      this.closest('form').submit();"
      style="padding: 0 !important;">
      {{ __('ログアウト') }}
    </x-responsive-nav-link>
  </form>
</div>

<hr class="border-black opacity-50 border-2">
